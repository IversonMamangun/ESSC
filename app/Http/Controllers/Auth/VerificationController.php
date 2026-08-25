<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\VerificationService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function send(SendOtpRequest $request, VerificationService $service)
    {
        $result = $service->sendPhoneOtp($request->phone, $request->purpose);

        return response()->json([
            'message' => $result['resumed'] ? 'A code is already active for this number.' : 'OTP sent.',
            'expires_in' => $result['expires_in'],
        ]);
    }

    public function verify(VerifyOtpRequest $request, VerificationService $service) 
    {
        $token = $service->verifyPhoneOtp(
            $request->phone,
            $request->otp,
            $request->purpose
        );

        return response()->json([
            'verification_token' => $token,
        ]);
    }
}
