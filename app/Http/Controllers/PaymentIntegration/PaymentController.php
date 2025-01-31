<?php

namespace App\Http\Controllers\PaymentIntegration;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Order;
use App\Models\Payment\PaymentTransaction;
use App\Models\User\User;
use App\Services\Basket\BasketService;
use App\Services\Basket\CheckoutService;
use App\Services\KapitalBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $kapitalBankService;
    protected $basketService;
    protected $checkoutService;

    public function __construct(KapitalBankService $kapitalBankService, BasketService $basketService, CheckoutService $checkoutService)
    {
        $this->kapitalBankService = $kapitalBankService;
        $this->basketService = $basketService;
        $this->checkoutService = $checkoutService;
    }

    /**
     * Create a common payment order.
     */

    public function initiatePayment( Request $request )
    {
        $user = Auth::user();

        // Retrieve user's cart
        $totalAmount = $this->basketService->calculateBasketTotal( $request->apply_bonus );

        // Call KapitalBank API to create an order
        $response = $this->kapitalBankService->createOrder([
            'typeRid' => 'Order_SMS',
            'amount' => $totalAmount,
            'currency' => "AZN",
            'language' => "az",
            'description' => "",
        ]);

        if (!isset($response['order']['id']) || !isset($response['order']['hppUrl'])) {
            return response()->json(['error' => 'Failed to create payment order'], 500);
        }

        // Save transaction in our database for tracking
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'order_id' => $response['order']['id'], // Store KapitalBank order_id
            'password' => $response['order']['password'], // Store password for verification
            'amount' => $totalAmount,
            'status' => 'pending', // Not paid yet
        ]);

        // Return KapitalBank's payment URL to the frontend
        $paymentUrl = $response['order']['hppUrl'] . '?id=' . $response['order']['id'] . '&password=' . $response['order']['password'];

        return response()->json(['success' => true, 'payment_url' => $paymentUrl]);
    }

    /**
     * Handle payment callback.
     */
    public function verifyPayment(Request $request)
    {
        $user = Auth::user();

        // Find the transaction
        $transaction = PaymentTransaction::where('order_id', $request->order_id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Invalid or already processed transaction'], 400);
        }

        // Fetch order details from KapitalBank
        $orderDetails = $this->kapitalBankService->getOrderDetails( $transaction->order_id );

        if ($orderDetails['order']['status'] === 'FullyPaid') {
            $transaction->update(['status' => 'paid']);

            $order = $this->checkoutService->checkout( $request->apply_bonus, $transaction->id );

            return response()->json(['success' => true, 'order_id' => $order->id]);
        } else {
            // Payment failed or is still pending
            return response()->json(['error' => 'Payment verification failed or pending'], 400);
        }
    }



    /**
     * Handle payment callback.
     */
    public function paymentCallback()
    {
        return "payment result";
    }



    /**
     * Execute clearing for preauthorized transactions.
     */
    public function executeClearing(Request $request)
    {
        $validated = $request->validate([
            'orderId' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        try {
            $response = $this->kapitalBankService->executeClearing($validated['orderId'], $validated['amount']);

            return response()->json(['success' => true, 'message' => 'Clearing successful', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
