<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\ConversationIndexResource;
use App\Http\Resources\Seller\ConversationShowResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

        $conversation->update(['store_unread_count' => 0]);

        return Inertia::render('seller/conversation/Show', [
            'conversation' => ConversationShowResource::make($conversation)->resolve(),
            'store' => $conversation->store,
        ]);
    }
}
