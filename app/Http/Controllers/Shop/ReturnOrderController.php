<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Shop\ReturnCreateResource;
use App\Enums\OrderReturnReason;
use App\Models\Order;
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
}
