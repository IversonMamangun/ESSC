<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     * * @param Request $request
     * @param Store|null $store  // Laravel automatically fetches the store by slug, or passes null if omitted.
     */
    public function index(Request $request, Store $store = null)
    {
        $user = $request->user();

        // 1. Fetch the user's previous chat conversations
        // (You will need to adjust this query based on how your database is set up)
        $conversations = []; 
        /* Example:
        $conversations = Conversation::where('user_id', $user->id)
            ->with('store')
            ->latest('updated_at')
            ->get();
        */

        // 2. Fetch messages if a specific store is active
        $messages = [];
        if ($store) {
            // Fetch messages between the auth user and this specific store
            /* Example:
            $messages = Message::where('user_id', $user->id)
                ->where('store_id', $store->id)
                ->orderBy('created_at', 'asc')
                ->get();
            */
        }

        // 3. Send the data to your Vue component
        return Inertia::render('shop/customer/chat/Index', [
            // Only pass the specific store data needed for the UI, not the whole model
            'activeStore' => $store ? [
                'id' => $store->id,
                'name' => $store->name,
                'slug' => $store->slug,
                'logo_url' => $store->logo_url,
            ] : null,
            
            'conversations' => $conversations,
            'messages' => $messages,
        ]);
    }
}