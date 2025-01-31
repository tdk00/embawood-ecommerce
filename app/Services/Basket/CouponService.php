<?php

namespace App\Services\Basket;

use App\Models\Discount\Coupon;
use App\Models\Discount\UsedCoupon;
use Illuminate\Support\Facades\Auth;


class CouponService
{
    protected $basketService;

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    public function attachCouponToBasket($couponCode)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            throw new \Exception('İstifadəçi tapılmadı.');
        }

        $coupon = Coupon::where('code', $couponCode)->first();
        if (!$coupon) {
            throw new \Exception('Kupon tapılmadı.');
        }

        if (!$coupon->is_active || $coupon->start_date->isFuture() || $coupon->end_date->isPast()) {
            throw new \Exception('Kupon aktiv deyil.');
        }

        if (UsedCoupon::where('coupon_id', $coupon->id)->where('user_id', $user->id)->exists()) {
            throw new \Exception('Bu kuponu artıq istifadə etmisiniz.');
        }

        $basketTotal = $this->calculateBasketTotal();
        if ($basketTotal === false) {
            throw new \Exception('Səbət artıq kuponla bağlıdır.');
        }

        if ($basketTotal < $coupon->min_required_amount || ($coupon->max_required_amount && $basketTotal > $coupon->max_required_amount)) {
            throw new \Exception('Səbət məbləği kupon tələblərinə uyğun deyil.');
        }

        $user->coupons()->attach($coupon->id);
        return $this->basketService->getBasketData();
    }

    public function detachCouponFromBasket($couponCode)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            throw new \Exception('İstifadəçi tapılmadı.');
        }

        $coupon = Coupon::where('code', $couponCode)->first();
        if (!$coupon) {
            throw new \Exception('Kupon tapılmadı.');
        }

        $user->coupons()->detach($coupon->id);
        return $this->basketService->getBasketData();
    }

    private function calculateBasketTotal()
    {
        $basket = $this->basketService->getBasketData();
        return $basket['applied_coupon'] !== null ? false : $basket['final_total'];
    }
}
