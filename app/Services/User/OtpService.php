<?php

namespace App\Services\User;

use App\Models\User\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected $smsApiUrl = 'https://send.atlsms.az:7443/bulksms/api';
    protected $smsLogin = 'embaser';
    protected $smsPassword = 'mbsr146';
    protected $smsTitle = 'Embawood';


    public function generateOtp($phone)
    {
        $otp = rand(1000, 9999); // Generate random OTP

        // Save OTP to the database
        Otp::create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP using ATL SMS API
        $response = $this->sendSms($phone, "Your OTP is $otp");
//
//        // Log or handle the response
//        if ($response['head']['responsecode'] !== '000') {
////            throw new \Exception('Failed to send OTP via SMS. Error Code: ' . $response['head']['responsecode']);
//        }

        return $otp; // For testing purposes only, remove this in production
    }

    /**
     * Send SMS via ATL SMS API.
     *
     * @param string $phone
     * @param string $message
     * @return array
     */
    private function sendSms($phone, $message)
    {
        $xmlRequest = $this->buildSmsXmlRequest($phone, $message);

        $response = Http::withHeaders([
            'Content-Type' => 'application/xml',
        ])
            ->withoutVerifying() // To skip SSL verification if using self-signed certificates
            ->send('POST', $this->smsApiUrl, [
                'body' => $xmlRequest, // Send raw XML as body
            ]);

        // Parse the XML response
        if ($response->ok()) {
            $responseXml = simplexml_load_string($response->body());
            return json_decode(json_encode($responseXml), true); // Convert XML to array
        } else {
            // Log error or throw exception
            throw new \Exception("Failed to send SMS. Status: {$response->status()}, Body: {$response->body()}");
        }
    }
    /**
     * Build XML request for sending SMS.
     *
     * @param string $phone
     * @param string $message
     * @return string
     */
    private function buildSmsXmlRequest($phone, $message)
    {
        // Format the phone number
        $formattedPhone = $this->formatPhoneNumber($phone);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<request>
    <head>
        <operation>submit</operation>
        <login>{$this->smsLogin}</login>
        <password>{$this->smsPassword}</password>
        <title>{$this->smsTitle}</title>
        <scheduled>now</scheduled>
        <isbulk>false</isbulk>
        <controlid>{$this->generateControlId()}</controlid>
    </head>
    <body>
        <msisdn>{$formattedPhone}</msisdn>
        <message>{$message}</message>
    </body>
</request>
XML;
    }

    /**
     * Helper function to format phone numbers.
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $cleanedPhone = preg_replace('/\D/', '', $phone);

        // Add the country code prefix (994) if missing
        if (substr($cleanedPhone, 0, 2) !== '99') {
            $cleanedPhone = '994' . $cleanedPhone;
        }

        return $cleanedPhone;
    }

    /**
     * Generate a unique control ID for each SMS request.
     *
     * @return string
     */
    private function generateControlId()
    {
        return uniqid('otp_', true);
    }

    public function verifyOtp($phone, $otp)
    {
        $otpRecord = Otp::where('phone', $phone)
            ->where('otp', $otp)
            ->orderBy('created_at', 'desc') // Get the most recent OTP
            ->first();

        if ($otpRecord && Carbon::now()->lt($otpRecord->expires_at)) {
            return true;
        }

        return false;
    }
}
