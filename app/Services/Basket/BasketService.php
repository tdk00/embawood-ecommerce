<?php

namespace App\Services\Basket;
use App\Models\Basket\BasketItem;
use App\Models\Basket\SafetyInformation;
use App\Models\Product\Product;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BasketService
{
    public function getBasketIdentifier()
    {
        if (Auth::guard('api')->check()) {
            return Auth::guard('api')->id();
        }

        if (!session()->has('basket_identifier')) {
            session()->put('basket_identifier', Str::uuid()->toString());
        }

        return session()->get('basket_identifier');
    }

    public function addToBasket($identifier, $product, $quantity)
    {
        if ($product->is_set) {
            $this->addSetToBasket($identifier, $product, $quantity);
        } else {
            $this->addIndividualProductToBasket($identifier, $product, $quantity);
        }
    }

    private function addSetToBasket($identifier, $product, $quantity)
    {
        $existingSetItem = BasketItem::where('identifier', $identifier)
            ->where('product_id', $product->id)
            ->whereNull('set_id')
            ->first();

        if ($existingSetItem) {
            $existingSetItem->quantity += $quantity;
            $existingSetItem->save();
            $this->updateSubproductsInSet($identifier, $product, $quantity);
        } else {
            BasketItem::create([
                'identifier' => $identifier,
                'product_id' => $product->id,
                'set_id' => null,
                'quantity' => $quantity
            ]);
            $this->addSubproductsToSet($identifier, $product, $quantity);
        }
    }

    private function addSubproductsToSet($identifier, $product, $quantity)
    {
        foreach ($product->products as $subproduct) {
            BasketItem::create([
                'identifier' => $identifier,
                'product_id' => $subproduct->id,
                'set_id' => $product->id,
                'quantity' => $subproduct->pivot->quantity * $quantity
            ]);
        }
    }

    private function updateSubproductsInSet($identifier, $product, $quantity)
    {
        foreach ($product->products as $subproduct) {
            $existingSubItem = BasketItem::where('identifier', $identifier)
                ->where('product_id', $subproduct->id)
                ->where('set_id', $product->id)
                ->first();

            if ($existingSubItem) {
                $existingSubItem->quantity += $subproduct->pivot->quantity * $quantity;
                $existingSubItem->save();
            } else {
                BasketItem::create([
                    'identifier' => $identifier,
                    'product_id' => $subproduct->id,
                    'set_id' => $product->id,
                    'quantity' => $subproduct->pivot->quantity * $quantity
                ]);
            }
        }
    }

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

    public function increaseQuantity($basketItemId)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return false;
        }

        $basketItem->quantity += 1;
        $basketItem->save();

        if (!$basketItem->set_id) {
            $this->updateSetSubproducts($basketItem);
        }

        return true;
    }

    public function decreaseQuantity($basketItemId)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem || $basketItem->quantity <= 1) {
            return false;
        }

        $basketItem->quantity -= 1;
        $basketItem->save();

        if (!$basketItem->set_id) {
            $this->updateSetSubproducts($basketItem);
        }

        return true;
    }

    private function updateSetSubproducts($basketItem)
    {
        $identifier = $this->getBasketIdentifier();
        $product = Product::find($basketItem->product_id);

        if (!$product) {
            return;
        }

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

    public function removeProductById($basketItemId)
    {
        $identifier = $this->getBasketIdentifier();
        $basketItem = BasketItem::where('identifier', $identifier)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return false;
        }

        try {
            if ($basketItem->set_id) {
                $basketItem->delete();
            } else {
                BasketItem::where('identifier', $identifier)
                    ->where('set_id', $basketItem->product_id)
                    ->delete();

                $basketItem->delete();
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Basket item removal failed', [
                'basket_item_id' => $basketItemId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getBasketData( $couponError = null )
    {
        $basketItems = $this->getBasketItems();

        $basket = $this->initializeBasket($couponError);

        $this->categorizeBasketItems($basket, $basketItems);
        $this->applyCouponDiscount($basket);

        $basket['safety_information'] = $this->getSafetyInformation();

        return $basket;
    }

    private function getBasketItems()
    {
        $identifier = auth()->id() ?? session()->get('basket_identifier');

        return BasketItem::where('identifier', $identifier)
            ->with(['product', 'product.products'])
            ->get();
    }

    private function initializeBasket($couponError)
    {
        return [
            'safety_information' => [],
            'total' => 0,
            'final_total' => 0,
            'product_discount_amount' => 0,
            'coupon_discount_amount' => 0,
            'sets' => [],
            'products' => [],
            'applied_coupon' => null,
            'coupon_valid' => true,
            'coupon_error' => $couponError,
        ];
    }

    private function categorizeBasketItems(&$basket, $basketItems)
    {
        $sets = $basketItems->filter(fn($item) => $item->product->is_set && is_null($item->set_id));
        $setProducts = $basketItems->filter(fn($item) => !is_null($item->set_id));
        $individualProducts = $basketItems->filter(fn($item) => is_null($item->set_id) && !$item->product->is_set);

        foreach ($sets as $set) {
            $this->handleSet($basket, $set, $setProducts);
        }

        foreach ($individualProducts as $item) {
            $this->handleIndividualProduct($basket, $item);
        }

        $basket['product_discount_amount'] = $basket['total'] - $basket['final_total'];
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


    private function handleIndividualProduct(array &$basket, $item)
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


    private function updateBasketTotals(array &$basket, $price, $finalPrice)
    {
        $basket['total'] += $price;
        $basket['final_total'] += $finalPrice;
    }

    private function applyCouponDiscount(array &$basket)
    {
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
    }

    private function getAppliedCoupon()
    {
        return Auth::guard('api')->user()?->coupons()?->first();
    }

    private function getSafetyInformation()
    {
        return SafetyInformation::all()->map(fn($info) => [
            'id' => $info->id,
            'title' => $info->title,
            'description' => $info->description,
            'icon' => url('storage/images/icons/' . $info->icon),
        ]);
    }

    public function calculateBasketTotal( $applyBonus = false ){
        $user = Auth::guard('api')->user();


        $basket = $this->getBasketData();
        $remainingBonus = $user->remaining_bonus_amount / 100;
        $remainingTotal = $basket['final_total'];

        if ($remainingBonus >= $remainingTotal) {
            $usedBonus = $remainingTotal;
        } else {
            $usedBonus = $remainingBonus;
        }



        if (!$applyBonus) {
            return $basket['final_total'];
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        $bonusSystemAvailable = filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if( ! $bonusSystemAvailable ) {
            return $basket['final_total'];
        }

        return $basket['final_total'] - $usedBonus;
    }



}
