<?php

namespace App\Observers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Services\CheckoutStatusSyncService;
use App\Services\ProductSoldCountService;

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

        if (in_array($order->status, [OrderStatus::CANCELLED, OrderStatus::DELIVERED], true)) {
            $order->loadMissing('checkout');

            if ($order->checkout) {
                app(CheckoutStatusSyncService::class)->sync($order->checkout);
            }
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
        app(ProductSoldCountService::class)->applyForOrder($order, $direction);
    }
}
