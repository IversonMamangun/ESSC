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

public function rate(Request $request, Order $order)
{
    if ($order->user_id !== $request->user()->id) {
        abort(403);
    }

    $order->load(['store:id,name', 'items:id,order_id,product_name,product_image,variant_name']);

    return Inertia::render('shop/customer/order/Rate', [
        'user' => $request->user()->only('name', 'phone', 'avatar'),
        'order' => $order,
    ]);
}

public function storeRating(Request $request, Order $order)
{
    if ($order->user_id !== $request->user()->id) {
        abort(403);
    }

    $validated = $request->validate([
        'items' => ['required', 'array'],
        'items.*.order_item_id' => ['required', 'exists:order_items,id'],
        'items.*.rating' => ['required', 'integer', 'min:1', 'max:5'],
        'items.*.comment' => ['nullable', 'string', 'max:1000'],
    ]);

    // Save records to your reviews table across each specific item sequence
    foreach ($validated['items'] as $itemReview) {
        // Example logic model tracking generation:
        // ProductReview::create([...$itemReview, 'user_id' => $request->user()->id]);
    }

    return redirect()->route('shop.orders.index')
        ->with('success', 'Thank you for your feedback!');
}
}
