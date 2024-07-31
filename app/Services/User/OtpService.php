<?php

namespace App\Services\User;

use App\Models\User\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function generateOtp($phone)
    {
        $otp = rand(1000, 9999);

        Otp::create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Simulate sending OTP via SMS
        // In a real-world scenario, integrate with an SMS gateway
        // Http::post('sms_gateway_url', ['phone' => $phone, 'message' => 'Your OTP is ' . $otp]);

        return $otp; // For testing purpose only, remove this in production
    }

    public function verifyOtp($phone, $otp)
    {
        $otpRecord = Otp::where('phone', $phone)->where('otp', $otp)->first();

        if ($otpRecord && Carbon::now()->lt($otpRecord->expires_at)) {
            return true;
        }

        return false;
    }
}
