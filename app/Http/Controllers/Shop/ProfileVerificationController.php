<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\SendProfileOtpRequest;
use App\Http\Requests\Shop\VerifyProfileOtpRequest;
use App\Services\VerificationService;
use App\Support\Phone;
use Illuminate\Support\Facades\Auth;

class ProfileVerificationController extends Controller
{
    public function send(SendProfileOtpRequest $request, VerificationService $service)
    {
        $target = Phone::toInternational(Auth::user()->phone);
        $result = $service->sendPhoneOtp($target, $request->purpose);

        return response()->json([
            'message' => $result['resumed'] ? 'A code is already active for your number.' : 'OTP sent.',
            'target' => $this->mask(Auth::user()->phone),
            'expires_in' => $result['expires_in'],
        ]);
    }

    public function verify(VerifyProfileOtpRequest $request, VerificationService $service)
    {
        $target = Phone::toInternational(Auth::user()->phone);
        $token = $service->verifyPhoneOtp($target, $request->otp, $request->purpose);

        return response()->json(['verification_token' => $token]);
    }

    private function mask(string $localPhone): string
    {
        return str_repeat('•', max(strlen($localPhone) - 4, 0)).substr($localPhone, -4);
    }
}