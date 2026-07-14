<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\OrderIndexResource;
use App\Http\Resources\Shop\OrderShowResource;
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
            'status' => ['sometimes', 'string', Rule::in(['all', 'to-pay', 'to-ship', 'to-receive', 'delivered', 'completed', 'cancelled', 'returned'])],
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
                'order_number',
                'status',
                'shipping_fee',
                'total',
                'delivered_at',
                'completed_at',
                'created_at',
            ])
            ->where('user_id', $userId)
            ->with([
                'store:id,name',
                'items:id,order_id,product_name,product_image,variant_name,price,quantity',
            ])
            ->withExists([
                'items as has_unreviewed_items' => fn ($query) => $query->whereDoesntHave('review'),
                'items as has_reviewed_items' => fn ($query) => $query->whereHas('review'),
                'items as has_returnable_items' => fn ($query) => $query->whereDoesntHave('orderReturn'),
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
            'delivered' => $query->where(
                'status',
                OrderStatus::DELIVERED
            ),
            'completed' => $query->where(
                'status',
                OrderStatus::COMPLETED
            ),
            'cancelled' => $query->where(
                'status',
                OrderStatus::CANCELLED
            ),
            'returned' => $query->whereIn('status', [
                OrderStatus::RETURN_REQUESTED,
                OrderStatus::RETURN_APPROVED,
                OrderStatus::RETURNED,
            ]),
            default => $query,
        };
    }

    public function show(Request $request, Order $order)
    {
        abort_unless(
            $request->user()->id === $order->user_id,
            403
        );

        $order->loadMissing([
            'store',
            'items.orderReturn.images',
        ]);

        return Inertia::render('shop/customer/order/Show', [
            'user' => $request->user()->only('name', 'phone', 'avatar'),
            'order' => OrderShowResource::make($order)->resolve(),
        ]);
    }

    public function complete(Request $request, Order $order)
    {
        abort_unless(
            $request->user()->id === $order->user_id,
            403
        );

        if ($order->status !== OrderStatus::DELIVERED) {
            abort(422, 'Invalid order state');
        }

        $order->update([
            'status' => OrderStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Order completed successfully.'
        );
    }

    public function cancel(Request $request, Order $order)
    {
        abort_unless(
            $request->user()->id === $order->user_id,
            403
        );

        if ($order->status !== OrderStatus::PENDING) {
            abort(422, 'Invalid order state');
        }

        $validated = $request->validate([
            'cancellation_reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $order->update([
            'status' => OrderStatus::CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return back()->with(
            'success',
            'Order cancelled successfully.'
        );
    }

}
