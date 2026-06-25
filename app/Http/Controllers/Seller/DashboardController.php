<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('store');

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }
        $store = $user->store;

        return Inertia::render('seller/dashboard/Index', [
            'store' => $store,
            'productsSummary' => $this->productsSummary($store),
            'ordersSummary' => $this->ordersSummary($store),
        ]);
    }

    private function productsSummary(Store $store): array
    {
        $total = $store->products()->count();
        $inactive = $store->products()->where('is_active', false)->count();

        $activeOutOfStock = $store->products()
            ->where('is_active', true)
            ->whereHas('variants')
            ->whereDoesntHave('variants', fn ($q) => $q->where('stock', '>', 0))
            ->count();
        $activeInStock = max($total - $inactive - $activeOutOfStock, 0);

        return [
            'total' => $total,
            'totalViews' => (int) $store->products()->sum('views'),
            'chart' => [
                ['key' => 'in_stock', 'label' => 'Active & In Stock', 'value' => $activeInStock],
                ['key' => 'out_of_stock', 'label' => 'Active & Out of Stock', 'value' => $activeOutOfStock],
                ['key' => 'inactive', 'label' => 'Inactive', 'value' => $inactive],
            ],
        ];
    }

    private function ordersSummary(Store $store): array
    {
        $counts = $store->orders()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $toConfirm = ($counts[OrderStatus::PENDING->value] ?? 0)
            + ($counts[OrderStatus::CONFIRMED->value] ?? 0);
        $toPack = $counts[OrderStatus::PROCESSING->value] ?? 0;
        $toShip = $counts[OrderStatus::PACKED->value] ?? 0;
        $cancelled = $counts[OrderStatus::CANCELLED->value] ?? 0;

        return [
            'total' => $toConfirm + $toPack + $toShip + $cancelled,
            'chart' => [
                ['key' => 'to_confirm', 'label' => 'To Confirm', 'value' => $toConfirm],
                ['key' => 'to_pack', 'label' => 'To Pack', 'value' => $toPack],
                ['key' => 'to_ship', 'label' => 'To Ship', 'value' => $toShip],
                ['key' => 'cancelled', 'label' => 'Cancelled', 'value' => $cancelled],
            ],
        ];
    }
}