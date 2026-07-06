<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ReviewCreateResource;
use App\Http\Requests\Shop\ReviewCreateRequest;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
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
            'order' => ReviewCreateResource::make($order)->resolve(),
        ]);
    }

    public function store(ReviewCreateRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->loadMissing('items');

        DB::transaction(function () use ($data, $order, $request): void {
            foreach ($data['items'] as $itemData) {
                $orderItem = $order->items->firstWhere('id', $itemData['order_item_id']);

                $review = Review::create([
                    'user_id' => $request->user()->id,
                    'order_item_id' => $orderItem->id,
                    'store_id' => $order->store_id,
                    'product_id' => $orderItem->product_id,
                    'rating' => $itemData['rating'],
                    'comment' => $itemData['comment'] ?? null,
                    'is_anonymous' => $itemData['is_anonymous'] ?? false,
                ]);

                foreach ($itemData['images'] ?? [] as $image) {
                    $review->images()->create([
                        'image' => $image->store('reviews/images', 'public'),
                    ]);
                }

                if (! empty($itemData['video'])) {
                    $review->update([
                        'video' => $itemData['video']->store('reviews/videos', 'public'),
                    ]);
                }
            }
        });

        return to_route('shop.orders.index')
            ->with('success', 'Thank you for your review!');
    }
}
