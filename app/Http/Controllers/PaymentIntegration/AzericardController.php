<?php
// app/Http/Controllers/AzericardController.php
namespace App\Http\Controllers\PaymentIntegration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Srustamov\Azericard\Azericard;
use Srustamov\Azericard\Events\OrderCompleted;
use Srustamov\Azericard\Exceptions\AzericardException;
use Srustamov\Azericard\Exceptions\FailedTransactionException;

class AzericardController extends Controller
{
    public function __construct()
    {

    }

    public function createOrder(Azericard $azericard, Request $request)
    {
        // Payment details
        $trtype = '0';
        $country = 'AZ';
        $merch_gmt = '+4';
        $backref = "https://embawood.az/index.php?route=extension/payment/azericard/callback";
        $desc = Config::get('azericard_payment.azericard_desc');
        $merch_name = Config::get('azericard_payment.azericard_merch_name');
        $merch_url = Config::get('azericard_payment.azericard_merch_url');
        $terminal = Config::get('azericard_payment.azericard_terminal');
        $email = Config::get('azericard_payment.azericard_email');
        $key = Config::get('azericard_payment.azericard_key');

        // Order details
        $amount = number_format(128, 0, '.', '');
        $currency = 'AZN';
        $order_id_formatted = str_pad("2777", 9, '0', STR_PAD_LEFT);
        $oper_time = gmdate("YmdHis");
        $nonce = substr(md5(rand()),0,16);

        // Determine the action URL (live or test)
        $action = Config::get('azericard_payment.azericard_testing') ?
            'https://testmpi.3dsecure.az/cgi-bin/cgi_link' :
            'https://mpi.3dsecure.az/cgi-bin/cgi_link';

        // Create the signature
        $to_sign =
            strlen($amount) . $amount .
            strlen($currency) . $currency .
            strlen($order_id_formatted) . $order_id_formatted .
            strlen($desc) . $desc .
            strlen($merch_name) . $merch_name .
            strlen($merch_url) . $merch_url .
            strlen($terminal) . $terminal .
            strlen($email) . $email .
            strlen($trtype) . $trtype .
            strlen($country) . $country .
            strlen($merch_gmt) . $merch_gmt .
            strlen($oper_time) . $oper_time .
            strlen($nonce) . $nonce .
            strlen($backref) . $backref;

        $p_sign = hash_hmac('sha1', $to_sign, hex2bin($key));

        // Language
        $lang = strtoupper("AZ");

        // Prepare data for view
        $data = [
            'action' => $action,
            'trtype' => $trtype,
            'country' => $country,
            'merch_gmt' => $merch_gmt,
            'backref' => $backref,
            'desc' => $desc,
            'merch_name' => $merch_name,
            'merch_url' => $merch_url,
            'terminal' => $terminal,
            'email' => $email,
            'p_sign' => $p_sign,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $order_id_formatted,
            'oper_time' => $oper_time,
            'nonce' => $nonce,
            'lang' => $lang,
        ];

        return view('admin.pages.payment_integration.azericard_form', $data);
    }

    public function callback(Azericard $azericard, Request $request)
    {
        $transaction = Transaction::find($request->get('order'));

        if (!$transaction || $transaction->status !== 'pending') {
            return response()->json(['message' => 'Order already processed or not found'], 409);
        }

        DB::beginTransaction();

        try {
            if ($azericard->completeOrder($request->all())) {
                $transaction->update([
                    'status' => 'success',
                    'rrn' => $request->get('rrn'),
                    'int_ref' => $request->get('int_ref'),
                    'processed_at' => now(),
                ]);

                $transaction->user->increment('balance', $transaction->amount);

                DB::commit();

                $transaction->user->notify(new TransactionSuccess($transaction));

                return response()->json(['message' => 'Order processed successfully'], 200);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                ]);

                DB::commit();

                logger()->error('Azericard payment failed', $request->all());

                return response()->json(['message' => 'Order processing failed'], 500);
            }
        } catch (FailedTransactionException $e) {
            DB::rollBack();
            logger()->error('Azericard | Message: ' . $e->getMessage(), $request->all());
        } catch (AzericardException $e) {
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
        } finally {
            info('Azericard payment callback called', $request->all());
        }
    }

    public function result($orderId)
    {
        $transaction = Transaction::find($orderId);

        if ($transaction && $transaction->status === 'success') {
            return view('payment.success');
        } elseif ($transaction && $transaction->status === 'pending') {
            return view('payment.pending');
        }

        return view('payment.failed');
    }
}
