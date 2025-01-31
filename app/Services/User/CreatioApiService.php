<?php
namespace App\Services\User;

use Illuminate\Support\Facades\Http;

class CreatioApiService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.creatio.api_url');
    }

    public function createOrUpdateClient($data, $accessToken)
    {
//        var_dump(($data));
//        die();
        $response = Http::withToken($accessToken)
            ->withoutVerifying() // Avoid verifying SSL if not needed
            ->withHeaders([
                'Accept' => 'application/json', // Ensure the response is returned as JSON
                'Content-Type' => 'application/json', // Set content-type for JSON payload
            ])
            ->post($this->baseUrl, $data); // Encode $data to JSON

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to create or update client: ' . $response->body());
    }
}
