<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserType;
use App\Services\VerificationService;
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
            'phone' => $verification->target,
            'user_type_id' => UserType::CUSTOMER,
            'password' => Hash::make($input['password']),
        ]);

        $this->verification->consumeToken(
            $input['verification_token']
        );

        return $user;
    }
}