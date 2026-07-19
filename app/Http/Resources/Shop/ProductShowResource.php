<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'rating' => (float) $this->rating,
            'reviews_count' => $this->reviews_count,
            'sold_count' => $this->sold_count,
            'categories' => $this->categories->map(fn ($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug
            ]),
            'images' => $this->images->map(fn ($img) => [
                'id' => $img->id,
                'url'        => Storage::url($img->image),
                'sort_order' => $img->sort_order,
            ]),
            'video' => $this->video
                ? Storage::url($this->video)
                : null,
            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'compare_price' => $variant->compare_price,
                    'stock' => $variant->stock,
                    'is_default' => $variant->is_default,
                    'image' => $variant->image
                        ? Storage::url($variant->image)
                        : null,
                    'attributes' => $variant->attributeValues->map(fn ($attrValue) => [
                        'name' => $attrValue->attribute->name,
                        'value' => $attrValue->value,
                    ]),
                ];
            })->values(),
            'store' => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'slug' => $this->store->slug,
                'logo' => $this->store->logo
                    ? Storage::url($this->store->logo)
                    : null,
                'is_official' => $this->store->is_official,
            ],
            'reviews' => $this->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'video' => $review->video ? Storage::url($review->video) : null,
                    'reply' => $review->reply,
                    'replied_at' => $review->replied_at?->toIso8601String(),
                    'created_at' => $review->created_at?->toIso8601String(),
                    'images' => $review->images->map(fn ($image) => [
                        'id' => $image->id,
                        'url' => Storage::url($image->image),
                    ]),
                    'user' => [
                        'name' => $review->is_anonymous 
                            ? $this->maskName($review->user->name) 
                            : $review->user->name,
                        'avatar' => $review->user->avatar
                            ? Storage::url($review->user->avatar)
                            : null
                    ],
                ];
            }),
        ];
    }

    private function maskName(?string $name): string
    {
        if (!$name) {
            return '*****';
        }
        return mb_substr($name, 0, 1) . '***' . mb_substr($name, -1, 1);
    }
}
