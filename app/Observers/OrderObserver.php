<?php

namespace App\Observers;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->status === OrderStatus::COMPLETED) {
            $this->adjustSoldCount($order, 1);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }

    protected function adjustSoldCount(Order $order, int $direction): void
    {
        $order->loadMissing('items');

        $quantities = $order->items
            ->groupBy('product_id')
            ->map(fn ($items) => $items->sum('quantity'));

        $totalQuantity = 0;

        foreach ($quantities as $productId => $quantity) {
            if (! $productId) {
                continue;
            }

            DB::table('products')
                ->where('id', $productId)
                ->increment('sold_count', $quantity * $direction);

            $totalQuantity += $quantity;
        }

        if ($totalQuantity > 0) {
            DB::table('stores')
                ->where('id', $order->store_id)
                ->increment('sold_count', $totalQuantity * $direction);
        }
    }
}
