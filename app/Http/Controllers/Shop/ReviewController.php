<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class ReviewController extends Controller
{
    
    public function create(Request $request, Order $order)
    {
        // Ensure the customer owns this order
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->loadMissing([
            'store',
            'items',
        ]);

        return Inertia::render('shop/customer/review/Create', [
            'user' => $request->user()->only('name', 'phone', 'avatar'),
            'order' => $order,
        ]);
    }
}
