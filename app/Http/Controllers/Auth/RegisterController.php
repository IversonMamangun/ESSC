<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Services\MoviderService;

class RegisterController extends Controller
{
    // 1. Handles POST /register/initiate AND POST /register/resend
    public function initiate(Request $request, MoviderService $movider)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:11', 'unique:users,phone'],
        ]);

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes, tied to the phone number
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));

        // Send via Movider
        $sent = $movider->sendOTP($request->phone, $otp);

        if (!$sent) {
            return response()->json(['message' => 'Failed to send OTP to your device.'], 500);
        }

        return response()->json(['message' => 'OTP sent successfully']);
    }

    // 2. Handles POST /register/verify
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->phone);

        // Check if OTP exists and matches
        if (!$cachedOtp || (string) $cachedOtp !== (string) $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // OTP is valid! Delete it so it can't be reused
        Cache::forget('otp_' . $request->phone);

        // Generate a secure verification token to allow the user to proceed to Step 3 (Password)
        $token = Str::random(60);
        
        // Store token for 15 minutes
        Cache::put('reg_token_' . $request->phone, $token, now()->addMinutes(15));

        return response()->json([
            'message' => 'OTP verified',
            'verification_token' => $token
        ]);
    }

    // 3. Handles POST /register/complete
    public function complete(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'verification_token' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $validToken = Cache::get('reg_token_' . $request->phone);

        if (!$validToken || $validToken !== $request->verification_token) {
            return response()->json(['message' => 'Session expired. Please restart registration.'], 403);
        }

        // Create the user in the database
        // User::create([
        //     'phone' => $request->phone,
        //     'password' => Hash::make($request->password),
        // ]);

        // Clear the token
        Cache::forget('reg_token_' . $request->phone);

        return response()->json(['message' => 'Account created successfully']);
    }
}