<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\ConversationIndexResource;
use App\Http\Resources\Seller\ConversationShowResource;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('store');

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }
        $store = $user->store;

        $conversations = $this->buildBaseQuery($user)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('seller/conversation/Index', [
            'store' => $store,
            'conversations' => ConversationIndexResource::collection($conversations),
        ]);
    }

    private function buildBaseQuery(User $user): Builder
    {
        return Conversation::query()
            ->where('store_id', $user->store->id)
            ->with(['user', 'latestMessage.attachments'])
            ->latest('last_message_at');
    }

    public function show(Conversation $conversation): Response
    {
        Gate::authorize('view', $conversation);

        $conversation->load([
            'user',
            'store',
            'messages' => fn ($query) => $query->oldest()->with(['sender', 'attachments']),
        ]);

        $conversation->markReadByStore();

        return Inertia::render('seller/conversation/Show', [
            'conversation' => ConversationShowResource::make($conversation)->resolve(),
            'store' => $conversation->store,
        ]);
    }

    public function check(Request $request, User $user): JsonResponse
    {
        $store = $request->user()->store;
        abort_unless($store, 404);

        $this->ensureBuyerHasOrderWithStore($store->id, $user->id);

        $conversation = Conversation::query()
            ->where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'data' => [
                'exists' => (bool) $conversation,
                'conversation_uuid' => $conversation?->uuid,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $store = $request->user()->store;
        abort_unless($store, 404);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $this->ensureBuyerHasOrderWithStore($store->id, $validated['user_id']);

        $conversation = Conversation::firstOrCreate([
            'store_id' => $store->id,
            'user_id' => $validated['user_id'],
        ]);

        return to_route('seller.conversations.show', $conversation)->with('success', 'Conversation created.');
    }

    private function ensureBuyerHasOrderWithStore(int $storeId, int $userId): void
    {
        $hasOrder = Order::query()
            ->where('store_id', $storeId)
            ->where('user_id', $userId)
            ->exists();

        abort_unless($hasOrder, 403);
    }
}
