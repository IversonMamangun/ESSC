<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Shop\ReturnCreateResource;
use App\Http\Requests\Shop\ReturnOrderCreateRequest;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderReturnReason;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderReturn;
use Inertia\Inertia;

class ReturnOrderController extends Controller
{
    public function create(Request $request, Order $order)
    {
        abort_unless($request->user()->id === $order->user_id, 403);

        $order->loadExists([
            'items as has_returnable_items' => fn ($query) =>
                $query->whereDoesntHave('orderReturn'),
        ]);

        abort_unless($order->isEligibleForReturn(), 403);

        $order->loadMissing([
            'store',
            'items.orderReturn',
        ]);

        $returnReasons = collect(OrderReturnReason::cases())->map(fn ($reason) => [
            'value' => $reason->value,
            'label' => $reason->label(),
        ]);

        return Inertia::render('shop/customer/return/Create', [
            'user' => $request->user()->only('name', 'phone', 'avatar'),
            'order' => ReturnCreateResource::make($order)->resolve(),
            'returnReasons' => $returnReasons,
        ]);
    }

    public function store(ReturnOrderCreateRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->loadMissing('items');

        DB::transaction(function () use ($data, $order): void {
            foreach ($data['items'] as $itemData) {
                $orderItem = $order->items->firstWhere('id', $itemData['order_item_id']);

                $orderReturn = OrderReturn::create([
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'reason' => $itemData['reason'],
                    'description' => $itemData['description'],
                ]);

                foreach ($itemData['images'] ?? [] as $image) {
                    $orderReturn->images()->create([
                        'image' => $image->store('returns/images', 'public'),
                    ]);
                }

                if (! empty($itemData['video'])) {
                    $orderReturn->update([
                        'video' => $itemData['video']->store('returns/videos', 'public'),
                    ]);
                }
            }

            $order->update([
                'status' => OrderStatus::RETURN_REQUESTED,
                'return_requested_at' => now(),
            ]);
        });

        return to_route('shop.orders.index')
            ->with('success', 'Your return request has been submitted!');
    }
}
