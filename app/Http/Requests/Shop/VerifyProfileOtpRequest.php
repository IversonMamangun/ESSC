<?php

namespace App\Http\Requests\Shop;

use App\Enums\VerificationCodePurpose;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyProfileOtpRequest extends FormRequest
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
            'purpose' => ['required', Rule::in([
                VerificationCodePurpose::CHANGE_EMAIL->value,
                VerificationCodePurpose::CHANGE_PHONE->value,
            ])],
            'otp' => ['required', 'digits:6'],
        ];
    }
}
