<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MessageAttachmentController extends Controller
{
    public function show(Conversation $conversation, MessageAttachment $attachment): StreamedResponse
    {
        Gate::authorize('view', $conversation);

        $attachment->loadMissing('message');
        abort_unless($attachment->message->conversation_id === $conversation->id, 404);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($attachment->disk);

        return $disk->response(
            $attachment->path,
            $attachment->original_name,
        );
    }
}
