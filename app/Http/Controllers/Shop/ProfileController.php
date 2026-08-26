<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Enums\VerificationCodePurpose;
use App\Http\Requests\Shop\UpdateProfileRequest;
use App\Http\Requests\Shop\ValidateProfileRequest;
use App\Services\VerificationService;
use App\Support\Phone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        return Inertia::render('shop/customer/account/profile/Edit', [
            'user' => Auth::user(),
        ]);
    }

    public function validate(ValidateProfileRequest $request)
    {
        return response()->json(['message' => 'Valid.']);
    }

    public function update(UpdateProfileRequest $request, VerificationService $verification)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validated();

        $newPhoneLocal = Phone::toLocal($validated['phone']);
        $phoneChanged = $newPhoneLocal !== $user->phone;
        $emailChanged = $validated['email'] !== $user->email;

        if ($phoneChanged || $emailChanged) {
            $purpose = $phoneChanged
                ? VerificationCodePurpose::CHANGE_PHONE
                : VerificationCodePurpose::CHANGE_EMAIL;

            // Always verify against the phone the user currently owns —
            // the OTP proves "this is really you", not "you own the new number".
            $expectedTarget = Phone::toInternational($user->phone);

            $code = $verification->validateToken($validated['verification_token'], $purpose->value);

            if (! $code->verified_at || $code->target !== $expectedTarget) {
                throw ValidationException::withMessages([
                    'verification_token' => 'Verification failed. Please verify again.',
                ]);
            }

            $verification->consumeToken($validated['verification_token']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('users/avatars', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $newPhoneLocal;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
