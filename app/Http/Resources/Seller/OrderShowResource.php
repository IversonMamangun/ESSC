<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; 

class OrderShowResource extends JsonResource
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
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shipping_fee,
            'discount' => $this->discount,
            'total' => $this->total,
            'notes' => $this->notes,
            'shipping_address' => [
                'recipient_name' => $this->recipient_name,
                'recipient_phone' => $this->recipient_phone,
                'region' => $this->region,
                'province' => $this->province,
                'city' => $this->city,
                'barangay' => $this->barangay,
                'street' => $this->street,
                'unit_bldg_house' => $this->unit_bldg_house,
                'postal_code' => $this->postal_code,
                'landmark' => $this->landmark,
            ],
            'items' => $this->items->map(fn ($item) => [
                'product_sku' => $item->product_sku,
                'product_name' => $item->product_name,
                'product_image' => Storage::url($item->product_image),
                'variant_name' => $item->variant_name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => round($item->price * $item->quantity, 2),
            ]),
            // null ones dropped
            'timestamps' => array_filter([
                'confirmed_at' => $this->confirmed_at,
                'processing_at' => $this->processing_at,
                'packed_at' => $this->packed_at,
                'shipped_at' => $this->shipped_at,
                'delivered_at' => $this->delivered_at,
                'completed_at' => $this->completed_at,
                'cancelled_at' => $this->cancelled_at,
                'return_requested_at' => $this->return_requested_at,
                'return_approved_at' => $this->return_approved_at,
                'returned_at' => $this->returned_at,
            ]),
            // only present when 'return' relation was eager loaded
            'return' => $this->whenLoaded('return', fn () => $this->return
                ? [
                    'reason' => $this->return->reason->value,
                    'reason_label' => $this->return->reason->label(),
                    'description' => $this->return->description,
                    'media_paths' => collect($this->return->media_paths ?? [])
                        ->map(fn ($path) => Storage::url($path))
                        ->all(),
                    'rejection_reason' => $this->return->rejection_reason,
                    'created_at' => $this->return->created_at,
                ]
                : null),
            'created_at' => $this->created_at,
        ];
    }
}
