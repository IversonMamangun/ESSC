<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ReviewUpdateRequest extends FormRequest
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
            'items as has_reviewed_items' => fn ($query) =>
                $query->whereHas('review'),
        ]);

        return $order->isEligibleForEditingRating();
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
            ],
            'items.*.rating' => ['required', 'integer', 'between:1,5'],
            'items.*.comment' => ['nullable', 'string', 'max:2000'],
            'items.*.is_anonymous' => ['nullable', 'boolean'],

            'items.*.images' => ['nullable', 'array'],
            'items.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'items.*.remove_image_ids' => ['nullable', 'array'],
            'items.*.remove_image_ids.*' => ['integer', Rule::exists('review_images', 'id')],

            'items.*.video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:20480'],
            'items.*.remove_video' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Cross-field check: existing images kept + new images uploaded must not exceed 5.
     * Can't express this with plain `max:5` since some images already exist server-side.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            $order->loadMissing('items.review.images');

            foreach ($this->input('items', []) as $index => $itemData) {
                $orderItem = $order->items->firstWhere('id', $itemData['order_item_id'] ?? null);

                if (! $orderItem?->review) {
                    continue;
                }

                $kept = $orderItem->review->images->count()
                    - count($itemData['remove_image_ids'] ?? []);
                $incoming = count($this->file("items.{$index}.images") ?? []);

                if (($kept + $incoming) > 5) {
                    $validator->errors()->add(
                        "items.{$index}.images",
                        'You can only have up to 5 images per review.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'items.*.order_item_id.in' => 'This item does not belong to the order.',
            'items.size' => 'You must submit a review for every item in this order.',
        ];
    }
}
