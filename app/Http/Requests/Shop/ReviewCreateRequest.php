<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewCreateRequest extends FormRequest
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

        // isEligibleForRating() relies on `return_exists` being loaded.
        $order->loadExists('return');
        $order->loadMissing('items:id,order_id');

        return $order->isEligibleForRating();
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
            'items' => ['required', 'array', 'size:' . $orderItemIds->count()],

            'items.*.order_item_id' => [
                'required',
                'integer',
                'distinct',
                Rule::in($orderItemIds),
                Rule::unique('reviews', 'order_item_id'),
            ],
            'items.*.rating' => ['required', 'integer', 'between:1,5'],
            'items.*.comment' => ['nullable', 'string', 'max:2000'],
            'items.*.is_anonymous' => ['nullable', 'boolean'],
            'items.*.images' => ['nullable', 'array', 'max:5'],
            'items.*.images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'items.*.video' => [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/quicktime,video/webm',
                'max:20480',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.order_item_id.in' => 'This item does not belong to the order.',
            'items.*.order_item_id.unique' => 'This item has already been reviewed.',
            'items.size' => 'You must submit a review for every item in this order.',
        ];
    }
}
