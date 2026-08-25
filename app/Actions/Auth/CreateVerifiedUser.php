<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserType;
use App\Services\VerificationService;
use App\Support\Phone;
use Illuminate\Support\Facades\Hash;

class CreateVerifiedUser
{
    public function __construct(protected VerificationService $verification)
    {
        //
    }

    public function create(array $input): User
    {
        $verification = $this->verification->validateToken(
            $input['verification_token'],
            'registration'
        );

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => Phone::toLocal($verification->target),
            'user_type_id' => UserType::CUSTOMER,
            'password' => Hash::make($input['password']),
        ]);

        $this->verification->deleteVerifications($verification->target);

        return $user;
    }
}