<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Enums\OrderStatus;

class OrderIndexResource extends JsonResource
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
            'order_number' => $this->order_number,
            'store_name' => $this->store?->name,
            'status' => match ($this->status) {
                OrderStatus::PENDING => 'to-pay',
                OrderStatus::CONFIRMED,
                OrderStatus::PROCESSING,
                OrderStatus::PACKED => 'to-ship',
                OrderStatus::SHIPPED => 'to-receive',
                OrderStatus::DELIVERED => 'delivered',
                OrderStatus::COMPLETED => 'completed',
                OrderStatus::CANCELLED => 'cancelled',
                OrderStatus::RETURNED => 'returned',
            },
            'status_label' => $this->status->label(),
            'shipping_fee' => (float) $this->shipping_fee,
            'total' => (float) $this->total,
            'items' => $this->items->map(fn ($item) => [
                'product_name' => $item->product_name,
                'product_image' => $item->product_image
                    ? Storage::url($item->product_image)
                    : null,
                'variant_name' => $item->variant_name,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
            ])->values(),
            'created_at' => $this->created_at,
        ];
    }
}
