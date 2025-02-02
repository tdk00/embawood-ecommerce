<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AzericardService
{
    private string $trtype = '0';
    private string $country = 'AZ';
    private string $merch_gmt = '+4';
    private string $currency = 'AZN';
    private string $lang = 'AZ';

    private string $backref;
    private string $desc;
    private string $merch_name;
    private string $merch_url;
    private string $terminal;
    private string $email;
    private string $key;
    private string $action;

    public function __construct()
    {
        $this->backref = route('payment.callback');
        $this->desc = Config::get('azericard_payment.azericard_desc');
        $this->merch_name = Config::get('azericard_payment.azericard_merch_name');
        $this->merch_url = Config::get('azericard_payment.azericard_merch_url');
        $this->terminal = Config::get('azericard_payment.azericard_terminal');
        $this->email = Config::get('azericard_payment.azericard_email');
        $this->key = Config::get('azericard_payment.azericard_key');
        $this->action = Config::get('azericard_payment.azericard_testing')
            ? 'https://testmpi.3dsecure.az/cgi-bin/cgi_link'
            : 'https://mpi.3dsecure.az/cgi-bin/cgi_link';
    }

    /**
     * Create an order and return the necessary parameters.
     */
    public function createOrder(array $data): array
    {
        $amount = $this->formatAmount($data['amount']);
        $orderId = $data['order_id'];
        $operTime = gmdate("YmdHis");
        $nonce = substr(md5(rand()),0,16);
        $pSign = $this->generateSignature($amount, $orderId, $operTime, $nonce);

        Log::info('Order status update attempt', [
            'backref' => $this->backref,
        ]);

        return [
            'action' => $this->action,
            'trtype' => $this->trtype,
            'country' => $this->country,
            'merch_gmt' => $this->merch_gmt,
            'backref' => route('payment.callback'),
            'desc' => $this->desc,
            'merch_name' => $this->merch_name,
            'merch_url' => $this->merch_url,
            'terminal' => $this->terminal,
            'email' => $this->email,
            'p_sign' => $pSign,
            'amount' => $amount,
            'currency' => $this->currency,
            'order_id' => $orderId,
            'oper_time' => $operTime,
            'nonce' => $nonce,
            'lang' => strtoupper($this->lang),
        ];
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 0, '.', '');
    }

    private function generateOrderId(string $orderId): string
    {
        return str_pad($orderId, 9, '0', STR_PAD_LEFT);
    }

    private function generateSignature(string $amount, string $orderId, string $operTime, string $nonce): string
    {
        $toSign =
            strlen($amount) . $amount .
            strlen($this->currency) . $this->currency .
            strlen($orderId) . $orderId .
            strlen($this->desc) . $this->desc .
            strlen($this->merch_name) . $this->merch_name .
            strlen($this->merch_url) . $this->merch_url .
            strlen($this->terminal) . $this->terminal .
            strlen($this->email) . $this->email .
            strlen($this->trtype) . $this->trtype .
            strlen($this->country) . $this->country .
            strlen($this->merch_gmt) . $this->merch_gmt .
            strlen($operTime) . $operTime .
            strlen($nonce) . $nonce .
            strlen($this->backref) . $this->backref;

        return hash_hmac('sha1', $toSign, hex2bin($this->key));
    }
}
