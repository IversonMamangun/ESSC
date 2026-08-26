<?php

namespace App\Http\Requests\Shop;

use App\Models\User;
use App\Support\Phone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $user = Auth::user();

        $newPhoneLocal = Phone::toLocal((string) $this->input('phone'));

        $sensitiveChanged = $this->input('email') !== $user->email
            || $newPhoneLocal !== $user->phone;

        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^639\d{9}$/',
                function (string $attribute, mixed $value, \Closure $fail) use ($user) {
                    $exists = User::where('phone', Phone::toLocal($value))
                        ->where('id', '!=', $user->id)
                        ->exists();

                    if ($exists) {
                        $fail('This phone number is already registered.');
                    }
                },
            ],

            'avatar' => ['nullable', 'image', 'max:2048'],
            'verification_token' => [Rule::requiredIf($sensitiveChanged), 'string'],
        ];
    }
}
