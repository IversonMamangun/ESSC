<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\OrderIndexResource;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(['all', 'to-pay', 'to-ship', 'to-receive', 'completed', 'cancelled', 'returned'])],
        ]);

        $filters = [
            'status' => $validated['status'] ?? 'all',
        ];

        return Inertia::render('shop/customer/order/Index', [
            'user' => $user->only('name', 'phone', 'avatar'),
            'orders' => OrderIndexResource::collection(
                $this->buildBaseQuery($user->id, $filters)
                ->latest()
                ->paginate(10)
                ->withQueryString()
            ),
            'filters' => $filters,
        ]);
    }

    private function buildBaseQuery(int $userId, array $filters): Builder
    {
        return Order::query()
            ->select([
                'id',
                'store_id',
                'status',
                'shipping_fee',
                'total',
                'created_at',
            ])
            ->where('user_id', $userId)
            ->with([
                'store:id,name',
                'items:id,order_id,product_name,product_image,variant_name,price,quantity',
            ])
            ->when(
                $filters['status'] !== 'all',
                fn (Builder $query) => $this->applyStatusFilter(
                    $query,
                    $filters['status']
                )
            );
    }

    private function applyStatusFilter(Builder $query, string $status): Builder 
    {
        return match ($status) {
            'to-pay' => $query->where('status', OrderStatus::PENDING),
            'to-ship' => $query->whereIn('status', [
                OrderStatus::CONFIRMED,
                OrderStatus::PROCESSING,
                OrderStatus::PACKED,
            ]),
            'to-receive' => $query->where(
                'status',
                OrderStatus::SHIPPED
            ),
            'completed' => $query->where(
                'status',
                OrderStatus::DELIVERED
            ),
            'cancelled' => $query->where(
                'status',
                OrderStatus::CANCELLED
            ),
            'returned' => $query->where(
                'status',
                OrderStatus::RETURNED
            ),
            default => $query,
        };
    }

    public function show(Request $request, Order $order)
{
    if ($order->user_id !== $request->user()->id) {
        abort(403, 'Unauthorized action.');
    }

    $order->load([
        'store:id,name',
        'items:id,order_id,product_name,product_image,variant_name,price,quantity',
        // 'shippingAddress' // Uncomment if you have a separate address relationship
    ]);

    return Inertia::render('shop/customer/order/Show', [
        'user' => $request->user()->only('name', 'phone', 'avatar'),
        'order' => $order,
    ]);
}
}
