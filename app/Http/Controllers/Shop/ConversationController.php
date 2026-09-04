<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ConversationIndexResource;
use App\Http\Resources\Shop\ConversationShowResource;
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
}
