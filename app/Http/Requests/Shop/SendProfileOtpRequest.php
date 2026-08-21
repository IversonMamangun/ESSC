<?php

namespace App\Http\Requests\Shop;

use App\Enums\VerificationCodePurpose;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SendProfileOtpRequest extends FormRequest
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
        $isPhoneChange = $this->input('purpose') === VerificationCodePurpose::CHANGE_PHONE->value;

        return [
            'purpose' => ['required', Rule::in([
                VerificationCodePurpose::CHANGE_EMAIL->value,
                VerificationCodePurpose::CHANGE_PHONE->value,
            ])],
            'phone' => [
                Rule::requiredIf($isPhoneChange),
                'nullable',
                'regex:/^639\d{9}$/',
                Rule::unique('users', 'phone')->ignore(Auth::id()),
            ],
        ];
    }
}
