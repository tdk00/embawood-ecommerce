<?php

namespace App\Http\Controllers\Basket;

use App\Http\Controllers\Controller;
use App\Services\Basket\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function checkout(Request $request)
    {
        $applyBonus = $request->apply_bonus ?? false;
        try {
            $order = $this->checkoutService->checkout( $applyBonus );
            return response()->json([
                'message' => 'Sifariş uğurla tamamlandı.',
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sifariş uğursuz oldu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
