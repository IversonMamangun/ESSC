<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\SendProfileOtpRequest;
use App\Http\Requests\Shop\VerifyProfileOtpRequest;
use App\Services\VerificationService;
use App\Enums\VerificationCodePurpose;
use Illuminate\Support\Facades\Auth;

class ProfileVerificationController extends Controller
{
    public function send(SendProfileOtpRequest $request, VerificationService $service)
    {
        [$purpose, $target] = $this->resolve($request->purpose, $request->phone);

        $service->sendPhoneOtp($target, $purpose->value);

        return response()->json([
            'message' => 'OTP sent.',
            'target' => $this->mask($target),
        ]);
    }

    public function verify(VerifyProfileOtpRequest $request, VerificationService $service)
    {
        [$purpose, $target] = $this->resolve($request->purpose, $request->phone);

        $token = $service->verifyPhoneOtp($target, $request->otp, $purpose->value);

        return response()->json(['verification_token' => $token]);
    }

    private function resolve(string $purpose, ?string $phone): array
    {
        $purpose = VerificationCodePurpose::from($purpose);

        $target = $purpose === VerificationCodePurpose::CHANGE_PHONE
            ? $phone
            : Auth::user()->phone;

        return [$purpose, $target];
    }

    private function mask(string $phone): string
    {
        return str_repeat('•', max(strlen($phone) - 4, 0)).substr($phone, -4);
    }
}