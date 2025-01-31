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
use App\Services\Basket\BasketService;
use App\Services\Basket\CheckoutService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BasketController extends Controller
{
    protected $basketService;
    protected $checkoutService;

    public function __construct(BasketService $basketService, CheckoutService $checkoutService)
    {
        $this->basketService = $basketService;
        $this->checkoutService = $checkoutService;
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ], [
            'product_id.required' => 'Məhsul seçilməlidir.',
            'product_id.exists' => 'Seçilmiş məhsul mövcud deyil.',
            'quantity.required' => 'Miqdar daxil edilməlidir.',
            'quantity.integer' => 'Miqdar tam ədəd olmalıdır.',
            'quantity.min' => 'Miqdar 1 və ya daha çox olmalıdır.'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'message' => 'Bu məhsul mövcud deyil.'
            ], 422);
        }

        $identifier = $this->basketService->getBasketIdentifier();
        $this->basketService->addToBasket($identifier, $product, $request->quantity);

        return response()->json([
            'message' => 'Məhsul səbətə əlavə edildi.',
            'basket' => $this->basketService->getBasketData()
        ]);
    }

    public function increaseProductQuantity(Request $request)
    {
        $request->validate([
            'basket_item_id' => 'required|exists:basket_items,id'
        ], [
            'basket_item_id.required' => 'Səbət məhsulunun ID-si tələb olunur.',
            'basket_item_id.exists' => 'Bu səbət məhsulu mövcud deyil.'
        ]);

        $updated = $this->basketService->increaseQuantity($request->basket_item_id);

        if (!$updated) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Səbətdə məhsul tapılmadı və ya miqdar artırıla bilmədi.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Məhsulun miqdarı artırıldı.',
            'basket' => $this->basketService->getBasketData()
        ]);
    }

    public function decreaseProductQuantity(Request $request)
    {
        $request->validate([
            'basket_item_id' => 'required|exists:basket_items,id'
        ], [
            'basket_item_id.required' => 'Səbət məhsulunun ID-si tələb olunur.',
            'basket_item_id.exists' => 'Bu səbət məhsulu mövcud deyil.'
        ]);

        $updated = $this->basketService->decreaseQuantity($request->basket_item_id);

        if (!$updated) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Səbətdə məhsul tapılmadı və ya miqdar azaldıla bilmədi.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Məhsulun miqdarı azaldıldı.',
            'basket' => $this->basketService->getBasketData()
        ]);
    }

    public function removeProduct(Request $request)
    {
        $request->validate([
            'basket_item_id' => 'required|exists:basket_items,id'
        ], [
            'basket_item_id.required' => 'Səbət məhsulunun ID-si tələb olunur.',
            'basket_item_id.exists' => 'Bu səbət məhsulu mövcud deyil.'
        ]);

        $removed = $this->basketService->removeProductById($request->basket_item_id);

        if (!$removed) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Məhsul səbətdən silinə bilmədi.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Məhsul səbətdən silindi.',
            'basket' => $this->basketService->getBasketData()
        ]);
    }

    public function getBasket()
    {
        $basketData = $this->basketService->getBasketData();
        $response = [
            'status' => 'success',
            'is_empty' => count($basketData['products']) == 0 && count($basketData['sets']) == 0,
            'message' => count($basketData['products']) > 0 || count($basketData['sets']) > 0 ? 'Səbət məlumatları gətirildi' : 'Səbət boşdur',
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

        $basketData = $this->basketService->getBasketData();

        if (empty($basketData['sets']) && empty($basketData['products'])) {
            return response()->json(['message' => 'Basket is empty'], 400);
        }

        $request->validate([
            'apply_bonus' => 'nullable|boolean',
        ]);

        try {

            $order = $this->checkoutService->checkout( $request->apply_bonus );

            return response()->json(['message' => 'Checkout successful', 'order' => $order], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
