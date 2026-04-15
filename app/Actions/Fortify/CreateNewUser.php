<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Actions\Fortify\PasswordValidationRules;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        // 1. Validate Phone instead of Email/Name
        Validator::make($input, [
            'phone' => ['required', 'string', 'min:10', 'max:15', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
            'user_type' => ['required', 'string', Rule::in(['buyer', 'seller'])],
        ])->validate();

        $userType = UserType::where('slug', $input['user_type'])->firstOrFail();

        // 2. Generate a temporary random name (e.g., User_84729) like Shopee does
        $temporaryName = 'User_' . rand(10000, 99999);

        // 3. Create the user
        return User::create([
            'name' => $temporaryName,
            'email' => null, 
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
            'user_type_id' => $userType->id,
        ]);
    }
}