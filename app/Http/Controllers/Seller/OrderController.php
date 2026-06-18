<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Resources\Seller\OrderIndexResource;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tab' => ['sometimes', 'string', Rule::in(['to-confirm', 'to-pack', 'to-ship', 'cancellation'])],
        ]);

        $filters = [
            'tab' => $validated['tab'] ?? 'to-confirm',
        ];

        $user = $request->user()->loadMissing(['store']);

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }

        $orders = $this->buildBaseQuery($user->store->id, $filters)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('seller/order/Index', [
            'store' => $user->store,
            'orders' => OrderIndexResource::collection($orders),
            'counts' => $this->getSummaryCounts($user->store->id),
            'filters' => $filters
        ]);
        
    }

    private function buildBaseQuery(int $storeId, array $filters): Builder
    {
        return Order::query()
            ->where('store_id', $storeId)
            ->with(['items'])
            ->when(
                $filters['tab'],
                fn (Builder $query) => $this->applyStatusFilter(
                    $query,
                    $filters['tab']
                )
            );
    }

    private function applyStatusFilter(Builder $query, string $status): Builder 
    {
        return match ($status) {
            'to-confirm' => $query->whereIn('status', [
                OrderStatus::PENDING,
                OrderStatus::CONFIRMED,
            ]),
            'to-pack' => $query->where('status', OrderStatus::PROCESSING),
            'to-ship' => $query->where('status', OrderStatus::PACKED),
            'cancellation' => $query->where('status', OrderStatus::CANCELLED),
            default => $query,
        };
    }

    private function getSummaryCounts(int $storeId): array
    {
        $counts = Order::query()
            ->where('store_id', $storeId)
            ->groupBy('status')
            ->selectRaw('status, count(*) as total')
            ->pluck('total', 'status');

        return [
            'to_confirm'   => ($counts->get(OrderStatus::PENDING->value, 0)) 
                            + ($counts->get(OrderStatus::CONFIRMED->value, 0)),
            'to_pack'      => $counts->get(OrderStatus::PROCESSING->value, 0),
            'to_ship'   => $counts->get(OrderStatus::PACKED->value, 0),
            'cancellation' => $counts->get(OrderStatus::CANCELLED->value, 0),
        ];
    }
}
