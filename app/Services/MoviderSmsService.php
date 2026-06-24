<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MoviderSmsService
{
    public function send(string $phone, string $message): void 
    {
        // trigger only in development testing
        if ($this->shouldFake()) {
            Log::info("[Movider:fake] SMS to {$phone} — {$message}");

            return;
        }

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

    // checker if env is local & no movider api key
    protected function shouldFake(): bool
    {
        return app()->environment('local', 'testing')
            && blank(config('services.movider.api_key'));
    }
}