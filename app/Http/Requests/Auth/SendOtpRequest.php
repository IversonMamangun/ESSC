<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\VerificationCodePurpose;
use App\Models\User;
use App\Support\Phone;

class SendOtpRequest extends FormRequest
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
        'phone' => [
                'required',
                'regex:/^639\d{9}$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (User::where('phone', Phone::toLocal($value))->exists()) {
                        $fail('This phone number is already registered.');
                    }
                },
            ],

            'purpose' => [
                'required',
                Rule::enum(VerificationCodePurpose::class),
            ],
        ];
    }
}
