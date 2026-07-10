<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderReturnReason;

class ReturnOrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        if (! $order || $order->user_id !== $this->user()->id) {
            return false;
        }

        $order->loadExists([
            'items as has_returnable_items' => fn ($query) =>
                $query->whereDoesntHave('orderReturn'),
        ]);

        return $order->isEligibleForReturn();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $order = $this->route('order');
        $orderItemIds = $order->items->pluck('id');

        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => [
                'required',
                'integer',
                'distinct',
                Rule::in($orderItemIds),
                Rule::unique('order_returns', 'order_item_id'),
            ],
            'items.*.reason' => ['required', Rule::enum(OrderReturnReason::class)],
            'items.*.description' => ['required', 'string', 'max:2000'],
            'items.*.images' => ['required', 'array', 'max:5'],
            'items.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'items.*.video' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/quicktime,video/webm',
                'max:20480',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Select at least one item to return.',
            'items.*.order_item_id.in' => 'This item does not belong to the order.',
            'items.*.order_item_id.unique' => 'A return has already been requested for this item.',
        ];
    }
}
