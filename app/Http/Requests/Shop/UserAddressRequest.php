<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\UserAddressLabel;

class UserAddressRequest extends FormRequest
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
            'label' => [
                'required',
                Rule::enum(UserAddressLabel::class),
            ],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_number' => ['required', 'string', 'max:20'],
            'region' => ['required', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255', 'required_unless:region,NCR'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'unit_bldg_house' => ['required', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:500'],
            'is_default' => ['boolean'],
        ];
    }
}
