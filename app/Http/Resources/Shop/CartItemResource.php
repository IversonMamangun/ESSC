<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $variant = $this->productVariant;
        $product = $variant?->product;

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product' => [
                'name' => $product?->name,
                'slug' => $product?->slug,
                'image' => $variant?->image
                    ?? $product?->images->first()?->image,
            ],
            'variant' => [
                'id' => $variant?->id,
                'sku' => $variant?->sku,
                'price' => (float) ($variant?->price ?? 0),
                'compare_price' => $variant?->compare_price
                    ? (float) $variant->compare_price
                    : null,
                'stock' => $variant?->stock,
                'weight' => $variant?->weight
                    ? (float) $variant->weight
                    : null,
            ],
            'attributes' => $variant?->attributeValues?->map(
                fn ($attributeValue) => [
                    'name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value,
                ]
            )->values(),
            'subtotal' => (float) (
                ($variant?->price ?? 0) * $this->quantity
            ),
        ];
    }
}
