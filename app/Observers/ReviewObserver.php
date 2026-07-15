<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->refreshProduct($review);
        $this->refreshStore($review);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        if ($review->wasChanged('rating')) {
            $this->refreshProduct($review);
            $this->refreshStore($review);
        }
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->refreshProduct($review);
        $this->refreshStore($review);
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        //
    }

    protected function refreshProduct(Review $review): void
    {
        if (! $review->product_id) {
            return;
        }

        $stats = Review::query()
            ->where('product_id', $review->product_id)
            ->selectRaw('COUNT(*) as count, COALESCE(AVG(rating), 0) as average')
            ->first();

        $review->product?->update([
            'reviews_count' => $stats->count,
            'rating' => round((float) $stats->average, 1),
        ]);
    }

    protected function refreshStore(Review $review): void
    {
        if (! $review->store_id) {
            return;
        }

        $stats = Review::query()
            ->where('store_id', $review->store_id)
            ->selectRaw('COUNT(*) as count, COALESCE(AVG(rating), 0) as average')
            ->first();

        $review->store?->update([
            'reviews_count' => $stats->count,
            'rating' => round((float) $stats->average, 1),
        ]);
    }

}
