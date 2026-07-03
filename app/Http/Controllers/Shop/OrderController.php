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
            'returned' => $query->where(
                'status',
                OrderStatus::RETURNED
            ),
            default => $query,
        };
    }

    public function show(Request $request, Order $order)
    {
        $order->loadMissing([
            'store',
            'items',
        ]);

        abort_unless(
            $request->user()->id === $order->user_id,
            403
        );

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

        $order->update([
            'status' => OrderStatus::CANCELLED,
            'cancelled_at' => now(),
            // extra fields for reason
        ]);

        return back()->with(
            'success',
            'Order cancelled successfully.'
        );
    }

public function rate(Request $request, Order $order)
    {
        // Ensure the customer owns this order
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
        // Ensure the customer owns this order
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
