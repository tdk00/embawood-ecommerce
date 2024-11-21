<?php

namespace App\Http\Controllers\Basket;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Basket\BasketItem;
use App\Models\Basket\SafetyInformation;
use App\Models\Bonus\Bonus;
use App\Models\Bonus\BonusExecution;
use App\Models\Bonus\BonusSetting;
use App\Models\Checkout\Order;
use App\Models\Checkout\OrderItem;
use App\Models\Checkout\OrderStatusHistory;
use App\Models\Discount\Coupon;
use App\Models\Discount\UsedCoupon;
use App\Models\Product\Product;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BasketController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/basket/add-product",
     *     operationId="addProductToBasket",
     *     tags={"Basket"},
     *     summary="Add a product to the basket",
     *     description="Adds a product or a set to the user's basket based on the product type. Returns the updated basket data.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", description="ID of the product to add", example=1),
     *             @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product successfully added to the basket",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message", example="Product added to basket"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid quantity",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Quantity must be greater than zero")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function addProduct(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            return response()->json(['message' => 'Quantity must be greater than zero'], 400);
        }

        // Determine the identifier (user ID or session ID)
        $identifier = $this->getBasketIdentifier();

        if ($product->is_set) {
            $this->addSetToBasket($identifier, $product, $quantity);
        } else {
            $this->addIndividualProductToBasket($identifier, $product, $quantity);
        }

        return response()->json(['message' => 'Product added to basket', 'basket' => $this->getBasketData()]);
    }

    // Merges the session basket into the user's basket upon login
    public function mergeBasketOnLogin()
    {
        if (Auth::guard('api')->check()) {
            $sessionIdentifier = session()->get('basket_identifier');
            $userId = Auth::guard('api')->id();

            if ($sessionIdentifier) {
                $sessionBasketItems = BasketItem::where('identifier', $sessionIdentifier)->get();

                foreach ($sessionBasketItems as $item) {
                    if ($item->set_id === null) {
                        $this->mergeIndividualOrSetItem($userId, $item);
                    } else {
                        $this->mergeSubproductInSet($userId, $item);
                    }
                }

                session()->forget('basket_identifier');
            }
        }
    }


    /**
     * @OA\Post(
     *     path="/api/basket/update-product-quantity",
     *     operationId="updateProductQuantity",
     *     tags={"Basket"},
     *     summary="Update the quantity of a product in the basket",
     *     description="Updates the quantity of a specific product in the user's basket. If the quantity is set to 0, the product is removed from the basket.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item to update", example=1),
     *             @OA\Property(property="quantity", type="integer", description="New quantity for the product. If set to 0, the product will be removed.", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantity updated successfully or product removed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Quantity updated"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid quantity",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="failure"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Quantity cannot be negative")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in basket",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="failure"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found in basket")
     *         )
     *     )
     * )
     */
    public function updateProductQuantity(Request $request)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItemId = $request->basket_item_id;
        $newQuantity = $request->quantity;

        if ($newQuantity < 0) {
            return response()->json(['status' => 'failure', 'message' => 'Quantity cannot be negative'], 400);
        }

        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return response()->json(['status' => 'failure', 'message' => 'Product not found in basket'], 404);
        }

        if ($newQuantity == 0) {
            $this->removeProductById($basketItemId);
            return response()->json(['status' => 'success', 'message' => 'Product removed from basket', 'basket' => $this->getBasketData()]);
        } else {
            $basketItem->quantity = $newQuantity;
            $basketItem->save();

            if (!$basketItem->set_id) {
                // Update quantities of subproducts if it's part of a set
                $product = Product::find($basketItem->product_id);
                foreach ($product->products as $subproduct) {
                    $subproductItem = BasketItem::where('identifier', $identifier)
                        ->where('product_id', $subproduct->id)
                        ->where('set_id', $basketItem->product_id)
                        ->first();

                    if ($subproductItem) {
                        $subproductItem->quantity = $subproduct->pivot->quantity * $newQuantity;
                        if ($subproductItem->quantity <= 0) {
                            $subproductItem->delete();
                        } else {
                            $subproductItem->save();
                        }
                    }
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Quantity updated', 'basket' => $this->getBasketData()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/basket/increase-product-quantity",
     *     operationId="increaseProductQuantity",
     *     tags={"Basket"},
     *     summary="Increase the quantity of a product in the basket",
     *     description="Increases the quantity of a specific product in the user's basket by 1. Returns the updated basket data.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item to update", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantity increased successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Quantity increased"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in basket",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="failure"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found in basket")
     *         )
     *     )
     * )
     */
    public function increaseProductQuantity(Request $request)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItemId = $request->basket_item_id;

        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return response()->json(['status' => 'failure', 'message' => 'Product not found in basket'], 404);
        }

        $basketItem->quantity += 1;
        $basketItem->save();

        if (!$basketItem->set_id) {
            // Update quantities of subproducts if it's part of a set
            $product = Product::find($basketItem->product_id);
            foreach ($product->products as $subproduct) {
                $subproductItem = BasketItem::where('identifier', $identifier)
                    ->where('product_id', $subproduct->id)
                    ->where('set_id', $basketItem->product_id)
                    ->first();

                if ($subproductItem) {
                    $subproductItem->quantity = $subproduct->pivot->quantity * $basketItem->quantity;
                    $subproductItem->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Quantity increased', 'basket' => $this->getBasketData()]);
    }


    /**
     * @OA\Post(
     *     path="/api/basket/decrease-product-quantity",
     *     operationId="decreaseProductQuantity",
     *     tags={"Basket"},
     *     summary="Decrease the quantity of a product in the basket",
     *     description="Decreases the quantity of a specific product in the user's basket by 1, provided the quantity is greater than 1. Returns the updated basket data.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item to update", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantity decreased successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Quantity decreased"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in basket",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="failure"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found in basket")
     *         )
     *     )
     * )
     */
    public function decreaseProductQuantity(Request $request)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItemId = $request->basket_item_id;

        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return response()->json(['status' => 'failure', 'message' => 'Product not found in basket'], 404);
        }

        if ($basketItem->quantity > 1) {
            $basketItem->quantity -= 1;
            $basketItem->save();

            if (!$basketItem->set_id) {
                // Update quantities of subproducts if it's part of a set
                $product = Product::find($basketItem->product_id);
                foreach ($product->products as $subproduct) {
                    $subproductItem = BasketItem::where('identifier', $identifier)
                        ->where('product_id', $subproduct->id)
                        ->where('set_id', $basketItem->product_id)
                        ->first();

                    if ($subproductItem) {
                        $subproductItem->quantity = max(1, $subproduct->pivot->quantity * $basketItem->quantity);
                        $subproductItem->save();
                    }
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Quantity decreased', 'basket' => $this->getBasketData()]);
    }

    /**
     * @OA\Post(
     *     path="/api/basket/remove-product",
     *     operationId="removeProductFromBasket",
     *     tags={"Basket"},
     *     summary="Remove a product from the basket",
     *     description="Removes a specific product from the user's basket. Returns the updated basket data upon success.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item to remove", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Product removed from basket"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in basket",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="failure"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found in basket")
     *         )
     *     )
     * )
     */
    public function removeProduct(Request $request)
    {
        $basketItemId = $request->basket_item_id;
        $removed = $this->removeProductById($basketItemId);

        if ($removed) {
            $basketData = $this->getBasketData();
            $message = 'Product removed from basket';

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'basket' => $basketData
            ]);
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => 'Product not found in basket'
            ], 404);
        }
    }

    private function removeProductById($basketItemId)
    {
        $identifier = $this->getBasketIdentifier();

        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if ($basketItem) {
            try {
                if ($basketItem->set_id) {
                    $basketItem->delete();
                } else {
                    // Delete all subproducts in one query
                    BasketItem::where('identifier', $identifier)
                        ->where('set_id', $basketItem->product_id)
                        ->delete();

                    $basketItem->delete();
                }
                return true; // Indicate success
            } catch (\Exception $e) {
                // Log the error for debugging
                Log::error('Failed to remove basket item', [
                    'basket_item_id' => $basketItemId,
                    'error' => $e->getMessage(),
                ]);
                return false; // Indicate failure
            }
        }

        return false; // Item was not found
    }

    private function getBasketData( $couponError = null )
    {
        $identifier = $this->getBasketIdentifier();
        $basketItems = BasketItem::where('identifier', $identifier)
            ->with(['product' => function($query) {
                $query->select('products.*');
            }, 'product.products' => function($query) {
                $query->select('products.*');
            }])->get();

        $basket = [
            'safety_information' => [],
            'total' => 0,
            'final_total' => 0,
            'product_discount_amount' => 0,
            'coupon_discount_amount' => 0,
            'sets' => [],
            'products' => [],
            'applied_coupon' => null,
            'coupon_valid' => true, // To track if the coupon is valid
            'coupon_error' => $couponError,
        ];

        // Step 1: Get Sets
        $sets = $basketItems->filter(function($item) {
            return $item->product->is_set && is_null($item->set_id);
        });

        // Step 2: Get Set Products
        $setProducts = $basketItems->filter(function($item) {
            return !is_null($item->set_id);
        });

        // Step 3: Get Individual Products
        $individualProducts = $basketItems->filter(function($item) {
            return is_null($item->set_id) && !$item->product->is_set;
        });

        // Step 4: Fill Sets with Products
        foreach ($sets as $set) {
            $this->handleSet($basket, $set, $setProducts);
        }

        // Step 5: Add Individual Products
        foreach ($individualProducts as $item) {
            $this->handleIndividualProduct($basket, $item);
        }


        $coupon = $this->getAppliedCoupon();

        $basket['product_discount_amount'] = $basket['total'] - $basket['final_total'];

        if ($coupon) {
            $basket['applied_coupon'] = $coupon->code;

            // Validate coupon requirements
            if ($basket['final_total'] < $coupon->min_required_amount) {
                $basket['coupon_valid'] = false;
                $basket['coupon_error'] = 'Basket total is less than the minimum required amount for this coupon.';
            } elseif ($coupon->max_required_amount && $basket['final_total'] > $coupon->max_required_amount) {
                $basket['coupon_valid'] = false;
                $basket['coupon_error'] = 'Basket total exceeds the maximum allowed amount for this coupon.';
            }



            // If coupon is valid, apply discount
            if ($basket['coupon_valid']) {
                // Initialize the discount amount
                $basket['coupon_discount_amount'] = 0;

                // Check the type of discount and calculate accordingly
                if ($coupon->type === 'percentage') {
                    // Calculate the discount amount based on the coupon's discount percentage
                    $basket['coupon_discount_amount'] = ($basket['final_total'] * $coupon->discount_percentage) / 100;
                } elseif ($coupon->type === 'amount') {
                    // Use the fixed discount amount
                    $basket['coupon_discount_amount'] = $coupon->amount;
                }

                // Ensure that the discount doesn't exceed the final total
                $basket['coupon_discount_amount'] = min($basket['coupon_discount_amount'], $basket['final_total']);

                // Subtract the discount from the final total
                $basket['final_total'] -= $basket['coupon_discount_amount'];
            }
        }

        $safetyInformations = SafetyInformation::all();

        $transformedSafetyInformations = $safetyInformations->map(function ($informationItem) {
            $informationItem->icon =  url('storage/images/icons/' . $informationItem->icon);
            return [
                'id' => $informationItem->id,
                'title' => $informationItem->title,
                'description' => $informationItem->description,
                'icon' => $informationItem->icon,
            ];
        });

        $basket['safety_information'] = $transformedSafetyInformations;

        return $basket;
    }

    private function getAppliedCoupon()
    {
        $user = Auth::guard('api')?->user();
        return $user?->coupons()?->first();
    }

    /**
     * @OA\Post(
     *     path="api/basket/attach-coupon",
     *     operationId="attachCouponToBasket",
     *     tags={"Basket"},
     *     summary="Attach a coupon to the user's basket",
     *     description="Validates and attaches a coupon to the user's basket if the requirements are met. Returns the updated basket data.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="coupon_code", type="string", description="The code of the coupon to attach", example="DISCOUNT10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coupon attachment response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Coupon applied"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     )
     * )
     */
    public function attachCouponToBasket( Request $request )
    {
        $user = Auth::guard('api')?->user();
        if (!$user) {
            return response()->json([
                'status' => 'failure',
                'message' => 'User not found',
                'basket' => $this->getBasketData('User not found')
            ], 200);
        }


        $couponCode = $request->coupon_code;

        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Coupon not found.',
                'basket' => $this->getBasketData('Coupon not found.')
            ], 200);
        }

        if (!$coupon->is_active || $coupon->start_date->isFuture() || $coupon->end_date->isPast()) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Coupon is not active.',
                'basket' => $this->getBasketData('Coupon is not active.')
            ], 200);
        }

        // Check if the user has already used the coupon in a previous checkout
        $alreadyUsed = UsedCoupon::where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'status' => 'failure',
                'message' => 'You have already used this coupon.',
                'basket' => $this->getBasketData('You have already used this coupon.')
            ], 200);
        }

        $basketTotal = $this->calculateBasketTotal();

        if( $basketTotal === false ) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Basket has attached coupon.',
                'basket' => $this->getBasketData('Basket has attached coupon.')
            ], 200);
        }

        if ($basketTotal < $coupon->min_required_amount || ($coupon->max_required_amount && $basketTotal > $coupon->max_required_amount)) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Basket total does not meet coupon requirements.',
                'basket' => $this->getBasketData('Basket total does not meet coupon requirements.')
            ], 200);
        }

        // Attach the coupon to the user's basket
        $user->coupons()->attach($coupon->id);


        $basketData = $this->getBasketData();
        $message = 'Coupon applied';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'basket' => $basketData
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/basket/detach-coupon",
     *     operationId="detachCouponFromBasket",
     *     tags={"Basket"},
     *     summary="Detach a coupon from the user's basket",
     *     description="Detaches a specific coupon from the user's basket and returns the updated basket data.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="coupon_code", type="string", description="The code of the coupon to detach", example="DISCOUNT10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coupon detached successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Coupon detached"),
     *             @OA\Property(property="basket", type="object", description="Updated basket data")
     *         )
     *     )
     * )
     */
    public function detachCouponFromBasket(Request $request)
    {

        $user = Auth::guard('api')?->user();
        if (!$user) {
            throw new \Exception('User not found.');
        }


        $couponCode = $request->coupon_code;

        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            throw new \Exception('Coupon not found.');
        }

        $user->coupons()->detach($coupon->id);

        $basketData = $this->getBasketData();
        $message = 'Coupon detached';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'basket' => $basketData
        ]);

    }

    private function calculateBasketTotal(){
        $basket = $this->getBasketData();
        if($basket['applied_coupon'] != null ) {
            return false;
        }
        else {
            return $basket['final_total'];
        }
    }

    private function handleSet(&$basket, $set, $setProducts)
    {

        $mainImage = url('storage/images/products/' . $set->product->main_image);
        $setItem = [
            "basket_item_id" => $set->id,
            "quantity" => $set->quantity,
            "item_product_id" => $set->product_id,
            "name" => $set->product->name,
            "discount" => 0, // Will be calculated later
            "price" => $set->product->price,
            "final_price" => $set->product->final_price,
            "basket_price" => 0,
            "basket_final_price" => 0,
            "basket_discount" => 0,
            "remaining_discount_seconds" => 0, // Will be calculated
            "has_unlimited_discount" => false,
            "has_limited_discount" => false,
            "main_image" => $mainImage,
            'average_rating' => $set->product->average_rating,
            "set_products" => []
        ];

        $latestDiscountEnd = 0;

        foreach ($setProducts as $setProduct) {
            if ($setProduct->set_id == $set->product_id) {
                $mainImage = url('storage/images/products/' . $setProduct->product->main_image);
                $productData = [
                    "basket_item_id" => $setProduct->id,
                    "quantity" => $setProduct->quantity,
                    "item_product_id" => $setProduct->product_id,
                    "name" => $setProduct->product->name,
                    "parent_basket_item_id" => $set->id,
                    "price" => $setProduct->product->price,
                    "discount" => $setProduct->product->discount,


                    "basket_price" => $setProduct->product->price * $setProduct->quantity,
                    "basket_final_price" => $setProduct->product->final_price * $setProduct->quantity,
                    "basket_discount" => $setProduct->product->discount,

                    "final_price" => $setProduct->product->final_price,
                    "remaining_discount_seconds" => $setProduct->product->remaining_discount_seconds,
                    "has_unlimited_discount" => $setProduct->product->has_unlimited_discount,
                    "has_limited_discount" => $setProduct->product->has_limited_discount,
                    "main_image" => $mainImage,
                    'average_rating' => $setProduct->product->average_rating,
                ];

                $setItem['set_products'][] = $productData;

                $setItem['basket_price'] += $setProduct->product->price * $setProduct->quantity;
                $setItem['basket_final_price'] += $setProduct->product->final_price * $setProduct->quantity;

                // Determine the latest discount end time
                if ($setProduct->product->remaining_discount_seconds > $latestDiscountEnd) {
                    $latestDiscountEnd = $setProduct->product->remaining_discount_seconds;
                }

                // Set discount types
                if ($setProduct->product->has_unlimited_discount) {
                    $setItem['has_unlimited_discount'] = true;
                    $setItem['has_limited_discount'] = false; // If unlimited, limited must be false
                } elseif (!$setItem['has_unlimited_discount'] && $setProduct->product->has_limited_discount) {
                    $setItem['has_limited_discount'] = true;
                }
            }
        }

        // Calculate discount percentage for the entire set
        if ($setItem['basket_price'] > 0) {
            $setItem['basket_discount'] = (($setItem['basket_price'] - $setItem['basket_final_price']) / $setItem['basket_price']) * 100;
        }

        if ($setItem['price'] > 0) {
            $setItem['discount'] = (($setItem['price'] - $setItem['final_price']) / $setItem['price']) * 100;
        }

        // Set the latest discount end time for the set
        $setItem['remaining_discount_seconds'] = $latestDiscountEnd;

        // Add the set to the basket
        $basket['sets'][] = $setItem;

        // Update the overall basket totals
        $this->updateBasketTotals($basket, $setItem['basket_price'], $setItem['basket_final_price']);
    }

    private function handleIndividualProduct(&$basket, $item)
    {

        $mainImage = url('storage/images/products/' . $item->product->main_image);
        $productData = [
            "basket_item_id" => $item->id,
            "quantity" => $item->quantity,
            "item_product_id" => $item->product_id,
            "name" => $item->product->name,
            "price" => $item->product->price,
            "discount" => $item->product->discount,
            "final_price" => $item->product->final_price,

            "basket_price" => $item->product->price * $item->quantity,
            "basket_final_price" => $item->product->final_price * $item->quantity,
            "basket_discount" => $item->product->discount,

            "remaining_discount_seconds" => $item->product->remaining_discount_seconds,
            "has_unlimited_discount" => $item->product->has_unlimited_discount,
            "has_limited_discount" => $item->product->has_limited_discount,
            "main_image" => $mainImage,
            'average_rating' => $item->product->average_rating,
        ];

        $basket['products'][] = $productData;

        // Update the totals with the product's price
        $this->updateBasketTotals($basket, $item->product->price * $item->quantity, $item->product->final_price * $item->quantity);
    }

    private function updateBasketTotals(&$basket, $price, $finalPrice)
    {
        $basket['total'] += $price;
        $basket['final_total'] += $finalPrice;
    }

    // Determines the identifier for the basket (user ID or session ID)
    private function getBasketIdentifier()
    {
        if (Auth::guard('api')->check()) {
            Log::info('User Authenticated', ['user_id' => Auth::guard('api')->id()]);
            return Auth::guard('api')->id();
        }

        if (!session()->has('basket_identifier')) {
            Log::info('Basket Identifier not found in session, creating new.');
            session()->put('basket_identifier', Str::uuid()->toString());
        } else {
            Log::info('Basket Identifier found in session.', ['identifier' => session()->get('basket_identifier')]);
        }

        return session()->get('basket_identifier');
    }

    // Adds an individual product to the basket
    private function addIndividualProductToBasket($identifier, $product, $quantity)
    {
        $existingProductItem = BasketItem::where('identifier', $identifier)
            ->where('product_id', $product->id)
            ->whereNull('set_id')
            ->first();

        if ($existingProductItem) {
            $existingProductItem->quantity += $quantity;
            $existingProductItem->save();
        } else {
            BasketItem::create([
                'identifier' => $identifier,
                'product_id' => $product->id,
                'set_id' => null,
                'quantity' => $quantity
            ]);
        }
    }

    // Adds a set and its subproducts to the basket
    private function addSetToBasket($identifier, $product, $quantity)
    {
        $existingSetItem = BasketItem::where('identifier', $identifier)
            ->where('product_id', $product->id)
            ->whereNull('set_id')
            ->first();

        if ($existingSetItem) {
            $existingSetItem->quantity += $quantity;
            $existingSetItem->save();

            foreach ($product->products as $subproduct) {
                $this->updateOrCreateSubproductInSet($identifier, $subproduct, $product->id, $quantity);
            }
        } else {
            $setItem = BasketItem::create([
                'identifier' => $identifier,
                'product_id' => $product->id,
                'set_id' => null,
                'quantity' => $quantity
            ]);

            foreach ($product->products as $subproduct) {
                BasketItem::create([
                    'identifier' => $identifier,
                    'product_id' => $subproduct->id,
                    'set_id' => $product->id,
                    'quantity' => $subproduct->pivot->quantity * $quantity
                ]);
            }
        }
    }

    // Updates or creates a subproduct in an existing set in the basket
    private function updateOrCreateSubproductInSet($identifier, $subproduct, $setId, $quantity)
    {
        $existingSubProductItem = BasketItem::where('identifier', $identifier)
            ->where('product_id', $subproduct->id)
            ->where('set_id', $setId)
            ->first();

        if ($existingSubProductItem) {
            $existingSubProductItem->quantity += $subproduct->pivot->quantity * $quantity;
            $existingSubProductItem->save();
        } else {
            BasketItem::create([
                'identifier' => $identifier,
                'product_id' => $subproduct->id,
                'set_id' => $setId,
                'quantity' => $subproduct->pivot->quantity * $quantity
            ]);
        }
    }

    // Merges an individual product or set item during login
    private function mergeIndividualOrSetItem($userId, $item)
    {
        $existingItem = BasketItem::where('identifier', $userId)
            ->where('product_id', $item->product_id)
            ->whereNull('set_id')
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $item->quantity;
            $existingItem->save();
        } else {
            $item->identifier = $userId;
            $item->save();
        }
    }

    // Merges a subproduct within a set during login
    private function mergeSubproductInSet($userId, $item)
    {
        $existingSetItem = BasketItem::where('identifier', $userId)
            ->where('product_id', $item->set_id)
            ->whereNull('set_id')
            ->first();

        if ($existingSetItem) {
            $existingSubProductItem = BasketItem::where('identifier', $userId)
                ->where('product_id', $item->product_id)
                ->where('set_id', $item->set_id)
                ->first();

            if ($existingSubProductItem) {
                $existingSubProductItem->quantity += $item->quantity;
                $existingSubProductItem->save();
            } else {
                $item->identifier = $userId;
                $item->save();
            }
        } else {
            $setItem = BasketItem::create([
                'identifier' => $userId,
                'product_id' => $item->set_id,
                'set_id' => null,
                'quantity' => 1 // Assuming 1 set was added
            ]);

            $item->identifier = $userId;
            $item->set_id = $setItem->product_id;
            $item->save();
        }
    }

    /**
     * @OA\Get(
     *     path="/api/basket",
     *     operationId="getBasket",
     *     tags={"Basket"},
     *     summary="Retrieve the current user's basket",
     *     description="Returns the details of the user's basket, including products, sets, discounts, and any applied coupons.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Basket retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="is_empty", type="boolean", description="Indicates if the basket is empty", example=false),
     *             @OA\Property(property="message", type="string", description="Message indicating the basket status", example="Basket retrieved successfully"),
     *             @OA\Property(property="total_items", type="integer", description="Total number of items in the basket", example=3),
     *             @OA\Property(
     *                 property="basket",
     *                 type="object",
     *                 description="Detailed basket data",
     *                 @OA\Property(property="total", type="number", format="float", description="Total price of items before discounts", example=200.5),
     *                 @OA\Property(property="final_total", type="number", format="float", description="Total price after applying discounts", example=180.0),
     *                 @OA\Property(property="product_discount_amount", type="number", format="float", description="Total discount amount from products", example=15.0),
     *                 @OA\Property(property="coupon_discount_amount", type="number", format="float", description="Total discount amount from coupons", example=5.5),
     *                 @OA\Property(
     *                     property="safety_information",
     *                     type="array",
     *                     description="List of safety information notices",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", description="ID of the safety notice", example=1),
     *                         @OA\Property(property="title", type="string", description="Title of the safety notice", example="Fire Safety"),
     *                         @OA\Property(property="description", type="string", description="Description of the safety notice", example="Keep flammable items away."),
     *                         @OA\Property(property="icon", type="string", description="Icon URL for the safety notice", example="https://example.com/icon.png")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="List of individual products in the basket",
     *                     @OA\Items(
     *                         @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item", example=1),
     *                         @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
     *                         @OA\Property(property="item_product_id", type="integer", description="ID of the product", example=101),
     *                         @OA\Property(property="name", type="string", description="Name of the product", example="Wooden Chair"),
     *                         @OA\Property(property="price", type="number", format="float", description="Price of the product", example=100.0),
     *                         @OA\Property(property="final_price", type="number", format="float", description="Final price after discounts", example=90.0),
     *                         @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="https://example.com/product.png")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="sets",
     *                     type="array",
     *                     description="List of sets in the basket",
     *                     @OA\Items(
     *                         @OA\Property(property="basket_item_id", type="integer", description="ID of the basket item", example=2),
     *                         @OA\Property(property="parent_basket_item_id", type="integer", description="ID of the parent basket item if applicable", example=1),
     *                         @OA\Property(property="quantity", type="integer", description="Quantity of the set", example=1),
     *                         @OA\Property(property="name", type="string", description="Name of the set", example="Living Room Set"),
     *                         @OA\Property(property="basket_price", type="number", format="float", description="Total price of the set before discounts", example=300.0),
     *                         @OA\Property(property="basket_final_price", type="number", format="float", description="Total price of the set after discounts", example=270.0)
     *                     )
     *                 ),
     *                 @OA\Property(property="applied_coupon", type="string", nullable=true, description="Code of the applied coupon, if any", example="DISCOUNT10"),
     *                 @OA\Property(property="coupon_valid", type="boolean", description="Indicates whether the applied coupon is valid", example=true),
     *                 @OA\Property(property="coupon_error", type="string", nullable=true, description="Error message related to the coupon, if invalid", example=null)
     *             )
     *         )
     *     )
     * )
     */
    public function getBasket()
    {
        $basketData = $this->getBasketData();


        $response = [
            'status' => 'success',
            'is_empty' => count($basketData['products']) == 0 && count($basketData['sets']) == 0,
            'message' => count($basketData['products']) > 0 || count($basketData['sets']) > 0 ? 'Basket retrieved successfully' : 'Your basket is empty',
            'total_items' => count($basketData['products']) + count($basketData['sets']),
            'basket' => $basketData,
        ];

        return response()->json($response);
    }


    /**
     * @OA\Post(
     *     path="/api/checkout",
     *     operationId="checkout",
     *     tags={"Checkout"},
     *     summary="Perform the checkout process",
     *     description="Completes the checkout process for the user's basket, applying bonuses and coupons where applicable, and creates an order. Clears the user's basket upon successful checkout.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="apply_bonus", type="boolean", nullable=true, description="Indicates if the user's bonus should be applied to the checkout", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Checkout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message", example="Checkout successful"),
     *             @OA\Property(
     *                 property="order",
     *                 type="object",
     *                 description="Order details",
     *                 @OA\Property(property="id", type="integer", description="Order ID", example=1),
     *                 @OA\Property(property="user_id", type="integer", description="ID of the user who placed the order", example=10),
     *                 @OA\Property(property="total", type="number", format="float", description="Total order amount before discounts", example=150.50),
     *                 @OA\Property(property="bonus_discount", type="number", format="float", description="Total discount from bonuses applied", example=20.0),
     *                 @OA\Property(property="coupon_discount", type="number", format="float", description="Total discount from coupons applied", example=10.0),
     *                 @OA\Property(property="item_discounts_total", type="number", format="float", description="Total discount from items", example=15.0),
     *                 @OA\Property(property="address", type="string", description="Delivery address for the order", example="John Doe, +123456789, 123 Main Street, Apt 4B, New York"),
     *                 @OA\Property(property="status", type="string", description="Order status", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", description="Timestamp when the order was created", example="2024-11-20T15:30:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime", description="Timestamp when the order was last updated", example="2024-11-20T15:35:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Basket is empty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Checkout failed due to a server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Checkout failed"),
     *             @OA\Property(property="error", type="string", description="Detailed error message", example="SQLSTATE[42S22]: Column not found: 1054 Unknown column...")
     *         )
     *     )
     * )
     */
    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $basketData = $this->getBasketData();

        if (empty($basketData['sets']) && empty($basketData['products'])) {
            return response()->json(['message' => 'Basket is empty'], 400);
        }

        $request->validate([
            'apply_bonus' => 'nullable|boolean',
        ]);

        // Find the user's default delivery address
        $deliveryAddress = $user->deliveryAddresses()->where('is_default', true)->first();
        if (!$deliveryAddress) {
            return response()->json(['message' => 'No default delivery address found'], 400);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $itemDiscountsTotal = 0;

            $order = Order::create([
                'user_id' => $userId,
                'total' => 0,
                'status' => 'pending',
                'address' => $deliveryAddress->fullname . ', ' . $deliveryAddress->phone . ', ' . $deliveryAddress->address_line_1 . ', ' . $deliveryAddress->address_line_2 . ', ' . $deliveryAddress->city,
                'used_bonus' => 0,
                'bonus_discount' => 0,
                'coupon_discount' => 0,
                'item_discounts_total' => 0,
            ]);

            $orderStatusHistory = OrderStatusHistory::create([
                'order_id' => $order['id'],
                'status' => 'pending',
            ]);

            foreach ($basketData['sets'] as $setItem) {
                OrderItem::create([
                    'order_id' => $order['id'],
                    'product_id' => $setItem['item_product_id'],
                    'set_id' => null,
                    'quantity' => $setItem['quantity'],
                    'price' => $setItem['basket_price'],
                    'discount_percentage' => $setItem['basket_discount'],
                    'discount_amount' => ($setItem['basket_price'] - $setItem['basket_final_price']),
                ]);

                foreach ($setItem['set_products'] as $setProductItem) {
                    OrderItem::create([
                        'order_id' => $order['id'],
                        'product_id' => $setProductItem['item_product_id'],
                        'set_id' => $setItem['item_product_id'],
                        'quantity' => $setProductItem['quantity'],
                        'price' => $setProductItem['basket_price'],
                        'discount_percentage' => $setProductItem['basket_discount'],
                        'discount_amount' => ($setProductItem['basket_price'] - $setProductItem['basket_final_price']),
                    ]);

                    $total += $setProductItem['basket_price'];
                    $itemDiscountsTotal += ($setProductItem['basket_price'] - $setProductItem['basket_final_price']);
                }
            }

            foreach ($basketData['products'] as $individualItem) {
                OrderItem::create([
                    'order_id' => $order['id'],
                    'product_id' => $individualItem['item_product_id'],
                    'set_id' => null,
                    'quantity' => $individualItem['quantity'],
                    'price' => $individualItem['basket_price'],
                    'discount_percentage' => $individualItem['basket_discount'],
                    'discount_amount' => ($individualItem['basket_price'] - $individualItem['basket_final_price']),
                ]);

                $total += $individualItem['basket_price'];
                $itemDiscountsTotal += ($individualItem['basket_price'] - $individualItem['basket_final_price']);
            }

            $couponDiscount = 0;
            if ($basketData['applied_coupon'] && $basketData['coupon_valid'] && $basketData['coupon_discount_amount'] > 0) {
                $couponDiscount = $basketData['coupon_discount_amount'];
            }

            $usedBonus = 0;
            if ($request->apply_bonus) {

                $remainingBonus = $user->remaining_bonus_amount / 100;
                $remainingTotal = $total - $couponDiscount - $itemDiscountsTotal;

                if ($remainingBonus >= $remainingTotal) {
                    $usedBonus = $remainingTotal;
                } else {
                    $usedBonus = $remainingBonus;
                }

                $user->remaining_bonus_amount -= $usedBonus * 100;
            }

            $setting = BonusSetting::where('type', 'order')->first();

            $finalTotal = $total - $couponDiscount - $itemDiscountsTotal - $usedBonus;

            $earnedBonus = $finalTotal * ($setting?->bonus_amount ?? 0);


            $order->update([
                'total' => $total,
                'bonus_discount' => $usedBonus,
                'coupon_discount' => $couponDiscount,
                'item_discounts_total' => $itemDiscountsTotal,
            ]);

            $user->remaining_bonus_amount += $earnedBonus;  // Convert to cents
            $user->total_bonus_amount += $earnedBonus;


            if ($setting) {
                $bonus = Bonus::create(['type' => 'order', 'amount' => $earnedBonus]);
                BonusExecution::create([
                    'user_id' => $user->id,
                    'bonus_id' => $bonus->id,
                    'executed_at' => Carbon::now(),
                ]);

            }

            $user->save();

            // Clear the user's basket
            BasketItem::where('identifier', $userId)->delete();

            DB::commit();

            return response()->json(['message' => 'Checkout successful', 'order' => $order], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
