<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MoviderSmsService
{
    public function send(string $phone, string $message): void 
    {
        $response = Http::asForm()
            ->acceptJson()
            ->post('https://api.movider.co/v1/sms', [
                'api_key' => config('services.movider.api_key'),
                'api_secret' => config('services.movider.api_secret'),
                'to' => $phone,
                'text' => $message,
                'from' => config('services.movider.sender'),
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Unable to send SMS.'
            );
        }
    }
}