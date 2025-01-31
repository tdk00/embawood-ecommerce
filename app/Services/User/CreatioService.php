<?php
namespace App\Services\User;

use Illuminate\Support\Facades\Http;

class CreatioService
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl;

    public function __construct()
    {
        $this->clientId = config('services.creatio.client_id');
        $this->clientSecret = config('services.creatio.client_secret');
        $this->tokenUrl = config('services.creatio.token_url');
    }

    public function getAccessToken()
    {
        $response = Http::asForm()->withoutVerifying()->post($this->tokenUrl, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        throw new \Exception('Failed to retrieve access token: ' . $response->body());
    }
}
