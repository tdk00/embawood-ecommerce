<?php

namespace App\Http\Controllers\Basket;

use App\Http\Controllers\Controller;
use App\Services\Basket\CouponService;
use App\Services\Basket\BasketService;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;
    protected $basketService;

    public function __construct(CouponService $couponService, BasketService $basketService)
    {
        $this->couponService = $couponService;
        $this->basketService = $basketService;
    }

    public function attachCouponToBasket(Request $request)
    {
        try {
            $basketData = $this->couponService->attachCouponToBasket($request->coupon_code);
            return response()->json([
                'status' => 'success',
                'message' => 'Kupon uğurla əlavə edildi.',
                'basket' => $basketData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
                'basket' => $this->basketService->getBasketData($e->getMessage())
            ], 200);
        }
    }

    public function detachCouponFromBasket(Request $request)
    {
        try {
            $basketData = $this->couponService->detachCouponFromBasket($request->coupon_code);
            return response()->json([
                'status' => 'success',
                'message' => 'Kupon uğurla silindi.',
                'basket' => $basketData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
                'basket' => $this->basketService->getBasketData($e->getMessage())
            ], 200);
        }
    }
}
