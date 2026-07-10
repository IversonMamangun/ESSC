<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReturnCreateResource extends JsonResource
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
                'product_image' => $item->product_image
                    ? Storage::url($item->product_image)
                    : null,
                'variant_name' => $item->variant_name,
                'quantity' => $item->quantity,
                'has_return' => $item->orderReturn !== null,
            ])
        ];

    }
}
