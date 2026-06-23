<?php

namespace App\Http\Requests\Auth;

use App\Models\UserType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            // Kept for the client round-trip, but the action trusts the
            // verified VerificationCode's target as the real phone, not this.
            'phone' => [
                'required',
                'string',
            ],

            'user_type_id' => [
                'required',
                Rule::in([UserType::SELLER, UserType::CUSTOMER]),
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],

            'verification_token' => [
                'required',
                'string',
            ],
        ];
    }
}