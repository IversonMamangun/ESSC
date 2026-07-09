<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ReviewCreateResource;
use App\Http\Resources\Shop\ReviewShowResource;
use App\Http\Resources\Shop\ReviewEditResource;
use App\Http\Requests\Shop\ReviewUpdateRequest;
use App\Http\Requests\Shop\ReviewCreateRequest;
use Illuminate\Support\Facades\Storage;
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
        abort_unless(
            $request->user()->id === $order->user_id,
            403
        );

        $order->loadExists([
            'items as has_unreviewed_items' => fn ($query) =>
                $query->whereDoesntHave('review'),
        ]);

        abort_unless(
            $order->isEligibleForRating(),
            403
        );

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

    public function show(Request $request, Order $order)
    {
        abort_unless($request->user()->id === $order->user_id, 403);

        $order->load([
            'store:id,name',
            'items.review.images',
        ]);

        return response()->json(
            ReviewShowResource::make($order)->resolve()
        );
    }

    public function edit(Request $request, Order $order)
    {
        abort_unless($request->user()->id === $order->user_id, 403);

        $order->loadExists([
            'items as has_reviewed_items' => fn ($query) =>
                $query->whereHas('review'),
        ]);

        abort_unless($order->isEligibleForEditingRating(), 403);

        $order->loadMissing([
            'store',
            'items.review.images',
        ]);

        return Inertia::render('shop/customer/review/Edit', [
            'user' => $request->user()->only('name', 'phone', 'avatar'),
            'order' => ReviewEditResource::make($order)->resolve(),
        ]);
    }

    public function update(ReviewUpdateRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->loadMissing('items');

        DB::transaction(function () use ($data, $order, $request): void {
            foreach ($data['items'] as $itemData) {
                $orderItem = $order->items->firstWhere('id', $itemData['order_item_id']);

                $review = Review::updateOrCreate(
                    ['order_item_id' => $orderItem->id],
                    [
                        'user_id' => $request->user()->id,
                        'store_id' => $order->store_id,
                        'product_id' => $orderItem->product_id,
                        'rating' => $itemData['rating'],
                        'comment' => $itemData['comment'] ?? null,
                        'is_anonymous' => $itemData['is_anonymous'] ?? false,
                    ]
                );

                // remove images the user unpinned
                if (! empty($itemData['remove_image_ids'])) {
                    $review->images()
                        ->whereIn('id', $itemData['remove_image_ids'])
                        ->get()
                        ->each(function ($image): void {
                            Storage::disk('public')->delete($image->image);
                            $image->delete();
                        });
                }

                // add newly uploaded images
                foreach ($itemData['images'] ?? [] as $image) {
                    $review->images()->create([
                        'image' => $image->store('reviews/images', 'public'),
                    ]);
                }

                // video removed without replacement
                if (! empty($itemData['remove_video']) && $review->video) {
                    Storage::disk('public')->delete($review->video);
                    $review->video = null;
                }

                // video replaced
                if (! empty($itemData['video'])) {
                    if ($review->video) {
                        Storage::disk('public')->delete($review->video);
                    }
                    $review->video = $itemData['video']->store('reviews/videos', 'public');
                }

                $review->save();
            }
        });

        return to_route('shop.orders.index')
            ->with('success', 'Your review has been updated!');
    }
}
