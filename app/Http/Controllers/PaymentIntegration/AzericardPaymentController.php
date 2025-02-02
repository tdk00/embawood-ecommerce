<?php

namespace App\Http\Controllers\PaymentIntegration;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Order;
use App\Models\Payment\AzericardPaymentTransaction;
use App\Models\Payment\PaymentTransaction;
use App\Models\User\User;
use App\Services\AzericardService;
use App\Services\Basket\BasketService;
use App\Services\Basket\CheckoutService;
use App\Services\KapitalBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AzericardPaymentController extends Controller
{
    protected $azericardService;
    protected $basketService;
    protected $checkoutService;

    public function __construct(AzericardService $azericardService, BasketService $basketService, CheckoutService $checkoutService)
    {
        $this->azericardService = $azericardService;
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



        $transaction = AzericardPaymentTransaction::create([
            'user_id' => $user->id,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $data = $this->azericardService->createOrder([
            'amount' => $totalAmount,
            'order_id' => $transaction->order_id,
        ]);

        $paymentReference = $transaction->order_id;
        Cache::put("payment_data_{$paymentReference}", $data, now()->addMinutes(15)); // Store for 15 mins

        // Generate a payment form URL
        $paymentFormUrl = route('payment.form', ['order_id' => $paymentReference]);

        return response()->json([
            'success' => true,
            'payment_url' => $paymentFormUrl,
        ]);
    }

    public function loadPaymentForm(Request $request)
    {
        $orderId = $request->query('order_id');

        // Retrieve stored payment data
        $data = Cache::get("payment_data_{$orderId}");

        Log::info('ALL DATA', $data);

        if (!$data) {
            abort(404, 'Payment session expired or invalid order ID.');
        }

        return view('admin.pages.payment_integration.azericard_form', compact('data'));
    }


    public function callback(Request $request)
    {
        Log::info('Azericard callback received', $request->all());

        // Check if ACTION exists and is successful
        if (!$request->has('ACTION') || $request->input('ACTION') !== "0") {
            return response()->json(['error' => 'Invalid ACTION'], 400);
        }

        // Extract variables from request
        $AMOUNT = $request->input('AMOUNT');
        $CURRENCY = $request->input('CURRENCY');
        $ORDER = $request->input('ORDER');
        $RRN = $request->input('RRN');
        $INT_REF = $request->input('INT_REF');
        $TERMINAL = Config::get('azericard_payment.azericard_terminal');
        $TRTYPE = '21';

        $oper_time = gmdate("YmdHis");
        $nonce = substr(md5(rand()), 0, 16);

        // Generate signature
        $to_sign = "" . strlen($ORDER) . $ORDER .
            strlen($AMOUNT) . $AMOUNT .
            strlen($CURRENCY) . $CURRENCY .
            strlen($RRN) . $RRN .
            strlen($INT_REF) . $INT_REF .
            strlen($TRTYPE) . $TRTYPE .
            strlen($TERMINAL) . $TERMINAL .
            strlen($oper_time) . $oper_time .
            strlen($nonce) . $nonce;

        $key_for_sign = Config::get('azericard_payment.azericard_key');
        $p_sign = hash_hmac('sha1', $to_sign, hex2bin($key_for_sign));

        // Prepare data for sending to Azericard
        $postData = [
            "AMOUNT" => $AMOUNT,
            "CURRENCY" => $CURRENCY,
            "ORDER" => $ORDER,
            "RRN" => $RRN,
            "INT_REF" => $INT_REF,
            "TERMINAL" => $TERMINAL,
            "TRTYPE" => $TRTYPE,
            "TIMESTAMP" => $oper_time,
            "NONCE" => $nonce,
            "P_SIGN" => $p_sign
        ];

        // Send request to Azericard
        $actionUrl = Config::get('azericard_payment.azericard_testing')
            ? 'https://testmpi.3dsecure.az/cgi-bin/cgi_link'
            : 'https://mpi.3dsecure.az/cgi-bin/cgi_link';

        $response = $this->sendCurlRequest($actionUrl, $postData);

        // Process response
        if ($response['content'] == '0') {

            if ($ORDER) {
                Log::info('Order status update attempt', [
                    'order_id' => $ORDER,
                    'current_status' => '',
                ]);
            } else {
                Log::warning('Order not found', ['order_id' => $ORDER]);
            }
        }

        return response()->json(['message' => 'Callback processed successfully']);
    }

    private function sendCurlRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        return [
            'errno'   => $err,
            'errmsg'  => $errmsg,
            'content' => $content,
        ];
    }
}
