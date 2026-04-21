<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle a traditional Email/Password authentication attempt.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard')); // Send to our Traffic Cop!
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.index');
    }

    // ==========================================
    // NEW OTP ENGINE LOGIC BELOW
    // ==========================================

    /**
     * Generate the OTP and send it to the user.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'exists:users,phone'],
        ], [
            'phone.exists' => 'We could not find an account with that phone number.'
        ]);

        // 1. Generate a random 6-digit code
        $otp = rand(100000, 999999);

        // 2. In production, trigger your SMS API here (Semaphore, Twilio, etc.)
        Log::info("OTP requested for {$request->phone}. Code is: {$otp}");

        // 3. Store the OTP and Phone securely in the session for 5 minutes
        $request->session()->put('otp', [
            'phone' => $request->phone,
            'code' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Redirect to the verification screen, flashing the code for easy local testing
        return redirect()->route('login.otp.verify')->with('status', "TESTING MODE: Your OTP is {$otp}");
    }

    /**
     * Show the screen to type in the OTP code.
     */
    public function showVerifyOtp(Request $request): Response|RedirectResponse
    {
        // If they try to visit this page without requesting a code, bounce them back
        if (!$request->session()->has('otp')) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/VerifyOtp', [
            'phone' => $request->session()->get('otp.phone'),
            'status' => session('status'), 
        ]);
    }

    /**
     * Check the OTP code and actually log the user in!
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        $otpData = $request->session()->get('otp');

        // Check if OTP expired or is missing
        if (!$otpData || now()->greaterThan($otpData['expires_at'])) {
            $request->session()->forget('otp');
            return redirect()->route('login')->withErrors(['phone' => 'Your code has expired. Please request a new one.']);
        }

        // Check if the code is wrong
        if ((string) $request->code !== (string) $otpData['code']) {
            return back()->withErrors(['code' => 'The code you entered is incorrect.']);
        }

        // 🟢 SUCCESS! Log the user in.
        $user = User::where('phone', $otpData['phone'])->firstOrFail();
        Auth::login($user);

        // Clean up the session so the OTP can't be used again
        $request->session()->regenerate();
        $request->session()->forget('otp');

        return redirect()->intended(route('dashboard')); // Send to Traffic Cop!
    }
}