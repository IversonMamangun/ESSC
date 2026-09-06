<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ConversationIndexResource;
use App\Http\Resources\Shop\ConversationShowResource;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Store;
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
        $user = $request->user();

        $conversations = $this->buildBaseQuery($user)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('shop/customer/conversation/Index', [
            'user' => $request->user()->only('name', 'phone', 'avatar'),
            'conversations' => ConversationIndexResource::collection($conversations),
        ]);
    }

    private function buildBaseQuery(User $user): Builder
    {
        return Conversation::query()
            ->where('user_id', $user->id)
            ->with(['store', 'latestMessage.attachments'])
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

        $conversation->markReadByUser();

        return Inertia::render('shop/customer/conversation/Show', [
            'conversation' => ConversationShowResource::make($conversation)->resolve(),
            'user' => $conversation->user,
        ]);
    }

    public function check(Request $request, Store $store): JsonResponse
    {
        $user = $request->user();
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
        $user = $request->user();
        $validated = $request->validate([
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ]);

        $this->ensureBuyerHasOrderWithStore($validated['store_id'], $user->id);

        $conversation = Conversation::firstOrCreate([
            'store_id' => $validated['store_id'],
            'user_id' => $user->id,
        ]);

        return to_route('shop.conversations.show', $conversation)->with('success', 'Conversation created.');
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
