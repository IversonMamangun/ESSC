<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductSoldCountService
{
    /**
     * Single order — used by OrderObserver.
     */
    public function applyForOrder(Order $order, int $direction): void
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

    /**
     * Many orders at once — used by the auto-complete cron.
     * Aggregates every item across the whole batch, then does
     * ONE update per table instead of one per order.
     */
    public function applyBulk(Collection $orders, int $direction): void
    {
        $productQuantities = [];
        $storeQuantities = [];

        foreach ($orders as $order) {
            $storeTotal = 0;

            foreach ($order->items as $item) {
                if (! $item->product_id) {
                    continue;
                }

                $productId = (int) $item->product_id;
                $productQuantities[$productId] =
                    ($productQuantities[$productId] ?? 0) + $item->quantity;

                $storeTotal += $item->quantity;
            }

            if ($storeTotal > 0) {
                $storeId = (int) $order->store_id;
                $storeQuantities[$storeId] =
                    ($storeQuantities[$storeId] ?? 0) + $storeTotal;
            }
        }

        $this->bulkIncrement('products', $productQuantities, $direction);
        $this->bulkIncrement('stores', $storeQuantities, $direction);
    }

    private function bulkIncrement(string $table, array $quantities, int $direction): void
    {
        if (empty($quantities)) {
            return;
        }

        $ids = array_map('intval', array_keys($quantities));

        $cases = collect($quantities)
            ->map(fn ($qty, $id) => 'WHEN ' . (int) $id . ' THEN sold_count + ' . ((int) $qty * $direction))
            ->implode(' ');

        DB::table($table)
            ->whereIn('id', $ids)
            ->update([
                'sold_count' => DB::raw("CASE id {$cases} END"),
            ]);
    }
}