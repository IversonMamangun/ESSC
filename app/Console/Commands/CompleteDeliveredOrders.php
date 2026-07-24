<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\ProductSoldCountService;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('app:complete-delivered-orders')]
#[Description('Mark delivered orders as completed once the grace period has elapsed.')]
class CompleteDeliveredOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ProductSoldCountService $soldCountService): int
    {
        $days = (int) Order::AUTO_COMPLETE_AFTER_DAYS;
        $cutoff = now()->subDays($days);
        $completed = 0;

        Order::query()
            ->select(['id', 'store_id', 'status', 'delivered_at'])
            ->where('status', OrderStatus::DELIVERED)
            ->where('delivered_at', '<=', $cutoff)
            ->with('items:id,order_id,product_id,quantity')
            ->chunkById(200, function ($orders) use (&$completed, $soldCountService) {
                try {
                    DB::transaction(function () use ($orders, $soldCountService) {
                        $soldCountService->applyBulk($orders, 1);

                        // NOTE: bulk update bypasses OrderObserver — sold-count logic is
                        // replicated via ProductSoldCountService::applyBulk() above.
                        // Any other side effects added to the observer's COMPLETED branch
                        // will need to be handled here too.
                        Order::whereIn('id', $orders->pluck('id'))
                            ->update([
                                'status' => OrderStatus::COMPLETED,
                                'completed_at' => now(),
                            ]);
                    });

                    $completed += $orders->count();
                } catch (\Throwable $e) {
                    Log::error('Failed to auto-complete order batch', [
                        'order_ids' => $orders->pluck('id')->all(),
                        'message' => $e->getMessage(),
                    ]);
                }
            });

        $this->info("Auto-completed {$completed} order(s).");

        return self::SUCCESS;
    }
}
