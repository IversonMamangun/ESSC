<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageCreateRequest;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function store(MessageCreateRequest $request, Conversation $conversation): RedirectResponse
    {
        DB::transaction(function () use ($request, $conversation) {
            $message = $conversation->messages()->create([
                'sender_id' => $request->user()->id,
                'body' => $request->validated('body'),
            ]);

            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store("conversations/{$conversation->uuid}", 'private');

                $message->attachments()->create([
                    'disk' => 'private',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            $conversation->update([
                'last_message_at' => $message->created_at,
                'user_unread_count' => $conversation->user_unread_count + 1,
            ]);
        });

        return back();
    }
}