<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Seller\ReviewIndexResource;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('store');

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }
        $store = $user->store;

        $reviews = $this->buildBaseQuery($user)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('seller/review/Index', [
            'store' => $store,
            'reviews' => ReviewIndexResource::collection($reviews),
        ]);
    }

    private function buildBaseQuery(User $user): Builder
    {
        return Review::query()
            ->where('store_id', $user->store->id)
            ->with([
                'images',
                'orderItem',
                'user',
            ])
            ->latest();
    }

    public function reply(Request $request, Review $review)
    {
        abort_unless(
            $review->store_id === $request->user()->store?->id,
            403
        );

        if ($review->reply !== null) {
            abort(422, 'This review already has a reply.');
        }

        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:500'],
        ]);

        $review->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
        ]);

        return back();
    }
}
