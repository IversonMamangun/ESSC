<?php

namespace App\Http\Requests\Seller;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $order && $order->store_id === $this->user()->store?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => [
                'required',
                'string',
                Rule::in([
                    'deliver',
                    'accept_return',
                    'decline_return',
                    'confirm_return',
                ]),
            ],
            'rejection_reason' => [
                'required_if:action,decline_return',
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}
