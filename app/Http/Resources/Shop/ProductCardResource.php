<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
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
            'slug' => $this->slug,
            'image' => $this->images->first()
                ? Storage::url($this->images->first()->image)
                : null,
            'price' => $this->defaultVariant?->price,
            'compare_price' => $this->defaultVariant?->compare_price,
            'stock' => (int) ($this->total_stock ?? 0),

            // 'review_count' => $this->reviews_count ?? 0,
            // 'average_rating' => $this->reviews_avg_rating ? round($this->reviews_avg_rating, 1) : 0.0,
        ];
    }
}
