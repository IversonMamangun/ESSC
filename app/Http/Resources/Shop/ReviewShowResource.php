<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReviewShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_number' => $this->order_number,
            'store_name' => $this->store?->name,
            'items' => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'product_image' => $item->product_image,
                'variant_name' => $item->variant_name,
                'quantity' => $item->quantity,
                'review' => $item->review ? [
                    'id' => $item->review->id,
                    'rating' => $item->review->rating,
                    'comment' => $item->review->comment,
                    'video' => $item->review->video,
                    'is_anonymous' => $item->review->is_anonymous,
                    'created_at' => $item->review->created_at?->toIso8601String(),
                    'images' => $item->review->images->map(fn ($image) => [
                        'id' => $image->id,
                        'url' => Storage::url($image->image),
                    ]),
                ] : null,
            ])->values(),
        ];
    }
}
