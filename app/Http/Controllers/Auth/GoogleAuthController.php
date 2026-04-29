<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleAuthController extends Controller
{
    // 1. Sends the user to the Google Login screen
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handles the user when Google sends them back
    public function callback()
    {
        try {
            // Get user data from Google
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by Google ID or Email, and Update/Create them
            $user = User::updateOrCreate([
                'google_id' => $googleUser->getId(),
            ], [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(), // Optional: Saves their profile pic
                // Generate a random password for new users (required by Laravel)
                'password' => bcrypt(Str::random(24)), 
            ]);

            // Log the user into your application
            Auth::login($user, true);

            // Redirect to your marketplace dashboard
            return redirect()->route('store.index');

        } catch (Exception $e) {
            // Log the error if something goes wrong
            return redirect()->route('login')->withErrors([
                'email' => 'Google login failed. Error: ' . $e->getMessage()
            ]);
        }
    }
}