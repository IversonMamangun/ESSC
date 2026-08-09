<?php

namespace App\Services;

use App\Enums\CheckoutStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Checkout;

class CheckoutStatusSyncService
{
    public function sync(Checkout $checkout): void
    {
        $checkout->loadMissing(['orders', 'payment']);

        $orders = $checkout->orders;

        if ($orders->isEmpty()) {
            return;
        }

        // All orders cancelled -> checkout + payment cancelled
        if ($orders->every(fn ($o) => $o->status === OrderStatus::CANCELLED)) {
            $allCancelledTotal = (float) $orders->sum('total');

            $checkout->update([
                'status' => CheckoutStatus::CANCELLED,
                'cancelled_amount' => $allCancelledTotal,
            ]);

            $checkout->payment?->update([
                'status' => PaymentStatus::CANCELLED,
                'cancelled_amount' => (int) round($allCancelledTotal * 100),
            ]);

            return;
        }

        $nonCancelled = $orders->reject(
            fn ($o) => $o->status === OrderStatus::CANCELLED
        );

        // Remaining (non-cancelled) orders must ALL be delivered.
        // If any of them is still pending/confirmed/processing/etc, do nothing yet.
        $allDelivered = $nonCancelled->every(
            fn ($o) => $o->status === OrderStatus::DELIVERED
        );

        if (! $allDelivered) {
            return;
        }

        $cancelledTotal = (float) $orders
            ->where('status', OrderStatus::CANCELLED)
            ->sum('total');

        $checkout->update([
            'status' => CheckoutStatus::PAID,
            'cancelled_amount' => $cancelledTotal,
        ]);

        $checkout->payment?->update([
            'status' => PaymentStatus::PAID,
            'cancelled_amount' => (int) round($cancelledTotal * 100),
        ]);
    }
}