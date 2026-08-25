<?php

namespace App\Services;

use App\Models\VerificationCode;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendOtpSmsJob;

class VerificationService
{
    public function __construct(protected MoviderSmsService $sms) 
    {
        // 
    }

    public function sendPhoneOtp(string $phone, string $purpose): array
    {
        $existing = VerificationCode::query()
            ->where('type', 'phone')
            ->where('target', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            return [
                'resumed' => true,
                'expires_in' => max(0, $existing->expires_at->getTimestamp() - now()->getTimestamp()),
            ];
        }

        VerificationCode::query()
            ->where('target', $phone)
            ->where('purpose', $purpose)
            ->update(['expires_at' => now()]);

        $otp = (string) random_int(100000, 999999);

        VerificationCode::create([
            'type' => 'phone',
            'purpose' => $purpose,
            'target' => $phone,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
        ]);

        SendOtpSmsJob::dispatch(phone: $phone, message: $otp);

        return [
            'resumed' => false,
            'expires_in' => 300,
        ];
    }

    public function verifyPhoneOtp(string $phone, string $otp, string $purpose): string 
    {
        $verification = VerificationCode::query()
            ->where('target', $phone)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $verification) {
            throw ValidationException::withMessages([
                'otp' => 'OTP not found.',
            ]);
        }

        if ($verification->isExpired()) {
            throw ValidationException::withMessages([
                'otp' => 'OTP expired.',
            ]);
        }

        if ($verification->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp' => 'Too many incorrect attempts. Please request a new code.',
            ]);
        }

        if (! Hash::check($otp, $verification->otp)) {
            $verification->increment('attempts');

            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP.',
            ]);
        }

        $token = Str::random(64);

        $verification->update([
            'verified_at' => now(),
            'verification_token' => $token,
        ]);

        return $token;
    }

    public function validateToken(string $token, string $purpose): VerificationCode {
        $verification = VerificationCode::query()
            ->where('verification_token', $token)
            ->where('purpose', $purpose)
            ->first();

        if (! $verification) {
            throw ValidationException::withMessages([
                'verification_token' => 'Invalid verification token.',
            ]);
        }

        return $verification;
    }

    // public function consumeToken(string $token): void {
    //     VerificationCode::query()
    //         ->where('verification_token', $token)
    //         ->update([
    //             'verification_token' => null,
    //         ]);
    // }

    public function deleteVerifications(string $target): void
    {
        VerificationCode::query()
            ->where('target', $target)
            ->delete();
    }
}