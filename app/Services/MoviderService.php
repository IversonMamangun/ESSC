<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoviderService
{
    public function sendOTP(string $phone, string $otp): bool
    {
        // Format the phone number to E.164 standard (+63...)
        // Since your frontend sends '9123456789', we prepend '+63'
        $formattedPhone = '+63' . ltrim($phone, '0');

        $response = Http::asForm()->post('https://api.movider.co/v1/sms', [
            'api_key' => config('services.movider.key'),
            'api_secret' => config('services.movider.secret'),
            'to' => $formattedPhone,
            'text' => "Your verification code is: {$otp}. Do not share this with anyone. Valid for 5 minutes."
        ]);

        if ($response->failed()) {
            // Log the error for debugging if the SMS fails
            Log::error('Movider SMS Failed', ['response' => $response->json()]);
            return false;
        }

        return true;
    }
}