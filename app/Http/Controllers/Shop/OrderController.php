<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
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
        $validated = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(['to-pay', 'to-ship', 'to-receive', 'completed', 'cancelled', 'returned'])],
        ]);

        $filters = [
            'status' => $validated['status'] ?? 'all',
        ];

        return Inertia::render('shop/customer/order/Index', [
            'orders' => OrderIndexResource::collection(
                $this->buildBaseQuery($filters)
                ->paginate(20)
                ->withQueryString()
            ),
            'filters' => $filters,
        ]);
    }

    private function buildBaseQuery(array $filters): Builder
    {
        // store name
        // order status
        // order shipping
        // order total
        // order item product name
        // order item variant name
        // order item price
        // order item quantity

        return Order::query()
            ->select([
                'id',
                'name',
                'slug',
                'is_featured',
                'created_at',
            ])
            ->with([
                'images:id,product_id,image,sort_order',
                'defaultVariant:id,product_id,price,compare_price',
            ])
            ->withSum('variants as total_stock', 'stock')
            ->having('total_stock', '>', 0)
            ->where('is_active', true)
            ->when(
                $filters['type'] === 'top-deals',
                fn (Builder $query) => $query
                    ->where('is_featured', true)
                    ->latest(),

                fn (Builder $query) => $query
                    ->latest()
            );
    }
}
