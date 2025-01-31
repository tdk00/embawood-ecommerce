<?php
namespace App\Services\Basket;
use App\Models\Basket\BasketItem;
use App\Models\Bonus\Bonus;
use App\Models\Bonus\BonusExecution;
use App\Models\Bonus\BonusSetting;
use App\Models\Checkout\Order;
use App\Models\Checkout\OrderItem;
use App\Models\Checkout\OrderStatusHistory;
use App\Models\Setting\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    protected $basketService;

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    public function checkout($applyBonus = false, $transactionId = null)
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('İstifadəçi tapılmadı.');
        }

        $basketData = $this->basketService->getBasketData();

        if (empty($basketData['sets']) && empty($basketData['products'])) {
            throw new \Exception('Səbət boşdur.');
        }

        $deliveryAddress = $user->deliveryAddresses()->where('is_default', true)->first();
        if (!$deliveryAddress) {
            throw new \Exception('Əsas çatdırılma ünvanı tapılmadı.');
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $itemDiscountsTotal = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'pending',
                'address' => $deliveryAddress->fullname . ', ' . $deliveryAddress->phone . ', ' . $deliveryAddress->address_line_1 . ', ' . $deliveryAddress->address_line_2 . ', ' . $deliveryAddress->city,
                'used_bonus' => 0,
                'bonus_discount' => 0,
                'coupon_discount' => 0,
                'item_discounts_total' => 0,
                'payment_transaction_id' => $transactionId,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            foreach ($basketData['sets'] as $setItem) {
                $this->createOrderItem($order->id, $setItem);
                foreach ($setItem['set_products'] as $setProductItem) {
                    $this->createOrderItem($order->id, $setProductItem, $setItem['item_product_id']);
                    $total += $setProductItem['basket_price'];
                    $itemDiscountsTotal += ($setProductItem['basket_price'] - $setProductItem['basket_final_price']);
                }
            }

            foreach ($basketData['products'] as $individualItem) {
                $this->createOrderItem($order->id, $individualItem);
                $total += $individualItem['basket_price'];
                $itemDiscountsTotal += ($individualItem['basket_price'] - $individualItem['basket_final_price']);
            }

            $couponDiscount = $this->calculateCouponDiscount($basketData);
            $usedBonus = $this->applyBonusDiscount($user, $applyBonus, $total, $couponDiscount, $itemDiscountsTotal);
            $finalTotal = $total - $couponDiscount - $itemDiscountsTotal - $usedBonus;

            $earnedBonus = $this->calculateEarnedBonus($finalTotal);

            $order->update([
                'total' => $total,
                'bonus_discount' => $usedBonus,
                'coupon_discount' => $couponDiscount,
                'item_discounts_total' => $itemDiscountsTotal,
            ]);

            $user->remaining_bonus_amount += $earnedBonus;
            $user->total_bonus_amount += $earnedBonus;
            $user->save();

            if ($earnedBonus > 0) {
                $this->recordBonus($user->id, $earnedBonus);
            }

            BasketItem::where('identifier', $user->id)->delete();

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Sifarişi tamamlamaq alınmadı: ' . $e->getMessage());
        }
    }

    private function createOrderItem($orderId, $item, $setId = null)
    {
        OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $item['item_product_id'],
            'set_id' => $setId,
            'quantity' => $item['quantity'],
            'price' => $item['basket_price'],
            'discount_percentage' => $item['basket_discount'],
            'discount_amount' => ($item['basket_price'] - $item['basket_final_price']),
        ]);
    }

    private function calculateCouponDiscount($basketData)
    {
        if ($basketData['applied_coupon'] && $basketData['coupon_valid'] && $basketData['coupon_discount_amount'] > 0) {
            return $basketData['coupon_discount_amount'];
        }
        return 0;
    }

    private function applyBonusDiscount($user, $applyBonus, $total, $couponDiscount, $itemDiscountsTotal)
    {
        if (!$applyBonus) {
            return 0;
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        $bonusSystemAvailable = filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if( ! $bonusSystemAvailable ) { return 0; }

        $remainingBonus = $user->remaining_bonus_amount / 100;
        $remainingTotal = $total - $couponDiscount - $itemDiscountsTotal;

        if ($remainingBonus >= $remainingTotal) {
            $usedBonus = $remainingTotal;
        } else {
            $usedBonus = $remainingBonus;
        }

        $user->remaining_bonus_amount -= $usedBonus * 100;
        return $usedBonus;
    }

    private function calculateEarnedBonus($finalTotal)
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $bonusSystemAvailable = filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if( ! $bonusSystemAvailable ) { return 0; }
        $setting = BonusSetting::where('type', 'order')->first();
        return $finalTotal * ($setting?->bonus_amount ?? 0);
    }

    private function recordBonus($userId, $earnedBonus)
    {

        $settings = Setting::pluck('value', 'key')->toArray();
        $bonusSystemAvailable = filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if( ! $bonusSystemAvailable ) { return false; }
        $bonus = Bonus::create(['type' => 'order', 'amount' => $earnedBonus]);
        BonusExecution::create([
            'user_id' => $userId,
            'bonus_id' => $bonus->id,
            'executed_at' => Carbon::now(),
        ]);
    }
}
