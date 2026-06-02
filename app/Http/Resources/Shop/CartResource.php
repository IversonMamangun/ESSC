<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalItems = $this->items->sum('quantity');
        $totalPrice = $this->items->sum(function ($item) {
            return $item->quantity * ($item->productVariant?->price ?? 0);
        });

        return [
            'id' => $this->id,
            'items' => CartItemResource::collection(
                $this->whenLoaded('items')
            ),
            'summary' => [
                'total_items' => $totalItems,
                'total_price' => (float) $totalPrice,
            ],
        ];
    }
}
