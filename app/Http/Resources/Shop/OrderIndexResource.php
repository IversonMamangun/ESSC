<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Enums\OrderStatus;
use App\models\Order;

class OrderIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isReturnGroup = in_array($this->status, [
            OrderStatus::RETURN_REQUESTED,
            OrderStatus::RETURN_APPROVED,
            OrderStatus::RETURNED,
        ], true);

        $allItems = $this->items;
        $displayItems = $isReturnGroup
            ? $allItems->filter(fn ($item) => $item->orderReturn !== null)->values()
            : $allItems;

        $shippingFee = $isReturnGroup ? 0.0 : (float) $this->shipping_fee;
        $total = $isReturnGroup 
            ? (float) $displayItems->sum(fn ($item) => $item->price * $item->quantity) 
            : (float) $this->total;

        if ($this->status === OrderStatus::DELIVERED && $this->delivered_at) {
            $autoCompleteAt = $this->delivered_at
                ->copy()
                ->addDays(Order::AUTO_COMPLETE_AFTER_DAYS);

            $autoCompleteInDays = max(0, (int) ceil(
                now()->floatDiffInDays($autoCompleteAt, false)
            ));
        }

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
                OrderStatus::RETURN_REQUESTED => 'return-requested',
                OrderStatus::RETURN_APPROVED => 'return-approved',
                OrderStatus::RETURNED => 'returned',
            },
            'status_label' => $this->status->label(),
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'items' => $displayItems->map(fn ($item) => [
                'product_name' => $item->product_name,
                'product_image' => $item->product_image
                    ? Storage::url($item->product_image)
                    : null,
                'variant_name' => $item->variant_name,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
            ])->values(),
            'is_rate_eligible' => $this->isEligibleForRating(),
            'is_edit_rate_eligible' => $this->isEligibleForEditingRating(),
            'is_return_eligible' => $this->isEligibleForReturn(),
            'created_at' => $this->created_at,
            'auto_complete_at' => $autoCompleteAt ?? null,
            'auto_complete_in_days' => $autoCompleteInDays ?? null,
        ];
    }
}