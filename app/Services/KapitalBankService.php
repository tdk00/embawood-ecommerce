<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KapitalBankService
{
    private $baseUrl;
    private $authHeader;

    public function __construct()
    {
        $this->baseUrl = config('kapitalbank.api_url');
        $this->authHeader = 'Basic ' . base64_encode(config('kapitalbank.username') . ':' . config('kapitalbank.password'));
    }

    /**
     * Create an order.
     */
    public function createOrder(array $data)
    {

        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => $this->authHeader,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/order", [
            'order' => array_merge($data, [
                'hppRedirectUrl' => config('kapitalbank.hpp_redirect_url'),
            ]),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to create order: ' . $response->body());
    }

    /**
     * Get order details.
     */
    public function getOrderDetails($orderId)
    {
        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => $this->authHeader,
        ])->get("{$this->baseUrl}/order/{$orderId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch order details: ' . $response->body());
    }

    /**
     * Execute a clearing transaction.
     */
    public function executeClearing($orderId, $amount)
    {
        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => $this->authHeader,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/order/{$orderId}/exec-tran", [
            'tran' => [
                'phase' => 'Clearing',
                'amount' => $amount,
            ],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to execute clearing: ' . $response->body());
    }
}
