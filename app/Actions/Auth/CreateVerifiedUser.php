<?php

namespace App\Actions\Auth;

use App\Models\User;
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
        $data = $this->verification
            ->validateToken(
                $input['verification_token'],
                'registration'
            );

        $user = User::create([
            'phone' => $data['phone'],
            'password' => Hash::make(
                $input['password']
            ),
        ]);
        
        $this->verification->consumeToken(
            $input['verification_token']
        );

        return $user;
    }
}