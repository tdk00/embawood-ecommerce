<?php

namespace App\Http\Controllers\Basket;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Basket\BasketItem;
use App\Models\Checkout\Order;
use App\Models\Checkout\OrderItem;
use App\Models\Discount\Coupon;
use App\Models\Discount\UserCoupon;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BasketController extends Controller
{
    public function addProduct(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            return response()->json(['message' => 'Quantity must be greater than zero'], 400);
        }

        $userId = Auth::id();

        if ($product->is_set) {
            $existingSetItem = BasketItem::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->whereNull('set_id')
                ->first();

            if ($existingSetItem) {
                return response()->json(['message' => 'Set is already in the basket'], 400);
            }

            $setItem = BasketItem::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'set_id' => null,
                'quantity' => $quantity
            ]);

            foreach ($product->products as $subproduct) {
                BasketItem::create([
                    'user_id' => $userId,
                    'product_id' => $subproduct->id,
                    'set_id' => $product->id,
                    'quantity' => $subproduct->pivot->quantity * $quantity
                ]);
            }
        } else {
            $existingProductItem = BasketItem::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->whereNull('set_id')
                ->first();

            if ($existingProductItem) {
                return response()->json(['message' => 'Product is already in the basket'], 400);
            }

            BasketItem::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'set_id' => null,
                'quantity' => $quantity
            ]);
        }

        return response()->json(['message' => 'Product added to basket', 'basket' => $this->getBasketData()]);
    }

    public function updateProductQuantity(Request $request)
    {
        $userId = Auth::id();
        $basketItemId = $request->basket_item_id;
        $newQuantity = $request->quantity;

        if ($newQuantity < 0) {
            return response()->json(['message' => 'Quantity cannot be negative'], 400);
        }

        $basketItem = BasketItem::where('user_id', $userId)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return response()->json(['message' => 'Product not found in basket'], 404);
        }

        if ($basketItem->set_id) {
            if ($newQuantity == 0) {
                $basketItem->delete();
            } else {
                $basketItem->quantity = $newQuantity;
                $basketItem->save();
            }
        } else {
            $basketItem->quantity = $newQuantity;
            $basketItem->save();

            $product = Product::find($basketItem->product_id);
            foreach ($product->products as $subproduct) {
                $subproductItem = BasketItem::where('user_id', $userId)
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

        return response()->json(['message' => 'Quantity updated', 'basket' => $this->getBasketData()]);
    }

    public function removeProduct(Request $request)
    {
        $userId = Auth::id();
        $basketItemId = $request->basket_item_id;

        $basketItem = BasketItem::where('user_id', $userId)
            ->where('id', $basketItemId)
            ->first();

        if (!$basketItem) {
            return response()->json(['message' => 'Product not found in basket'], 404);
        }

        if ($basketItem->set_id) {
            $basketItem->delete();
        } else {
            $subproducts = BasketItem::where('user_id', $userId)
                ->where('set_id', $basketItem->product_id)
                ->get();

            foreach ($subproducts as $subproduct) {
                $subproduct->delete();
            }

            $basketItem->delete();
        }

        return response()->json(['message' => 'Product removed from basket', 'basket' => $this->getBasketData()]);
    }

    private function getBasketData()
    {
        $userId = Auth::id();
        $basketItems = BasketItem::where('user_id', $userId)->with('product', 'product.products')->get();

        $basket = [
            'products' => [],
            'sets' => [],
            'total' => 0,
            'coupon_discount' => 0,
        ];

        $setProductIds = [];

        foreach ($basketItems as $item) {
            if ($item->set_id) {
                if (!isset($basket['sets'][$item->set_id])) {
                    $basket['sets'][$item->set_id] = [
                        'set' => $item->set,
                        'products' => []
                    ];
                }
                $basket['sets'][$item->set_id]['products'][] = $item;
            } else {
                if ($item->product->is_set) {
                    $setTotal = 0;
                    foreach ($item->product->products as $subproduct) {
                        $subproductItem = BasketItem::where('user_id', Auth::id())
                            ->where('product_id', $subproduct->id)
                            ->where('set_id', $item->product_id)
                            ->first();
                        if ($subproductItem) {
                            $setTotal += $subproductItem->product->price * $subproductItem->quantity;
                        } else {
                            $setTotal += $subproduct->price * $subproduct->pivot->quantity * $item->quantity;
                        }
                    }
                    $item->total_price = $setTotal;
                    $basket['sets'][$item->product_id] = [
                        'set' => $item,
                        'products' => []
                    ];
                    $basket['total'] += $setTotal;

                    foreach ($item->product->products as $subproduct) {
                        $setProductIds[] = $subproduct->id;
                    }

                    unset($item->product->products);
                } else {
                    $basket['products'][] = $item;
                    $basket['total'] += $item->product->price * $item->quantity;
                }
            }
        }

        foreach ($basketItems as $item) {
            if (!$item->set_id && !in_array($item->product_id, $setProductIds) && !$item->product->is_set) {
                $basket['total'] += $item->product->price * $item->quantity;
            }
        }

        return $basket;
    }

    public function getBasket()
    {
        return response()->json($this->getBasketData());
    }

    public function applyCoupon(Request $request)
    {
        $userId = Auth::id();
        $couponCode = $request->input('coupon_code');

        // Validate the coupon
        $coupon = Coupon::where('code', $couponCode)
            ->where('start_date', '<=', now())
            ->where('expiration_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid or expired coupon'], 400);
        }

        // Get the user coupon
        $userCoupon = UserCoupon::where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->where('is_used', false)
            ->first();

        if (!$userCoupon) {
            return response()->json(['message' => 'Coupon not available for this user'], 400);
        }

        // Get basket items
        $basketItems = BasketItem::where('user_id', $userId)->with('product')->get();
        if ($basketItems->isEmpty()) {
            return response()->json(['message' => 'Basket is empty'], 400);
        }

        // Calculate the discount
        $total = 0;
        $itemDiscountsTotal = 0;

        foreach ($basketItems as $item) {
            $price = $item->product->price * $item->quantity;
            $itemDiscountPercentage = $item->discount ?? 0;
            $itemDiscountAmount = $price * ($itemDiscountPercentage / 100);
            $finalPrice = $price - $itemDiscountAmount;

            if (!$item->product->is_set) {
                $total += $finalPrice;
            }

            $itemDiscountsTotal += $itemDiscountAmount;
        }

        // Apply coupon discount
        $couponDiscountAmount = $userCoupon->earned_amount;
        $total -= $couponDiscountAmount;

        // Return updated basket
        $updatedBasket = [
            'items' => $basketItems,
            'total' => $total,
            'item_discounts_total' => $itemDiscountsTotal,
            'coupon_discount' => $couponDiscountAmount,
        ];

        return response()->json($updatedBasket);
    }

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $basketItems = BasketItem::where('user_id', $userId)->with('product')->get();

        if ($basketItems->isEmpty()) {
            return response()->json(['message' => 'Basket is empty'], 400);
        }

        $request->validate([
            'delivery_address_id' => 'required|exists:user_delivery_addresses,id',
            'used_bonus' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        $deliveryAddress = UserDeliveryAddress::find($request->delivery_address_id);
        $usedBonus = $request->input('used_bonus', 0);

        if ($usedBonus > $user->remaining_bonus_amount) {
            return response()->json(['message' => 'Insufficient bonus amount'], 400);
        }

        $coupon = null;
        $couponDiscountAmount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('start_date', '<=', now())
                ->where('expiration_date', '>=', now())
                ->first();

            if (!$coupon) {
                return response()->json(['message' => 'Invalid or expired coupon'], 400);
            }

            $userCoupon = UserCoupon::where('user_id', $userId)
                ->where('coupon_id', $coupon->id)
                ->where('is_used', false)
                ->first();

            if (!$userCoupon) {
                return response()->json(['message' => 'Coupon not available for this user'], 400);
            }

            $couponDiscountAmount = $userCoupon->earned_amount;
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
                'used_bonus' => $usedBonus,
                'bonus_discount' => $usedBonus / 100,
                'coupon_discount' => 0,
                'item_discounts_total' => 0,
            ]);

            foreach ($basketItems as $item) {
                $price = $item->product->price * $item->quantity;
                $itemDiscountPercentage = $item->discount ?? 0;
                $itemDiscountAmount = $price * ($itemDiscountPercentage / 100);
                $finalPrice = $price - $itemDiscountAmount;

                if (!$item->product->is_set) {
                    $total += $finalPrice;
                }

                $itemDiscountsTotal += $itemDiscountAmount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'set_id' => $item->set_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'discount_percentage' => $itemDiscountPercentage,
                    'discount_amount' => $itemDiscountAmount,
                ]);
            }

            // Apply coupon discount
            if ($coupon) {
                $order->coupons()->attach($coupon->id);
                $total -= $couponDiscountAmount;
                $order->update(['coupon_discount' => $couponDiscountAmount]);

                // Mark the user coupon as used
                $userCoupon->update(['is_used' => true]);
            }

            // Apply used bonus
            $total -= $usedBonus;

            // Update order total and item discounts total
            $order->update([
                'total' => $total,
                'item_discounts_total' => $itemDiscountsTotal,
            ]);

            // Deduct used bonus from user's remaining bonus amount
            $user->remaining_bonus_amount -= $usedBonus;
            $user->save();

            // Clear the user's basket
            BasketItem::where('user_id', $userId)->delete();

            // Check if order qualifies for any new earned coupons
            $this->checkAndAssignEarnedCoupons($order);

            DB::commit();

            return response()->json(['message' => 'Checkout successful', 'order' => $order], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }

    private function checkAndAssignEarnedCoupons($order)
    {
        $coupons = Coupon::where('type', 'earned')
            ->where('start_date', '<=', now())
            ->where('expiration_date', '>=', now())
            ->get();

        foreach ($coupons as $coupon) {
            if ($order->total >= $coupon->coupon_min && $order->total <= $coupon->coupon_max) {
                $earnedAmount = $order->total * ($coupon->discount_percentage / 100);
                UserCoupon::create([
                    'user_id' => $order->user_id,
                    'coupon_id' => $coupon->id,
                    'earned_amount' => $earnedAmount,
                ]);
            }
        }
    }
}
