<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ConversationShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'store' => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'logo' => $this->store->logo
                    ? Storage::url($this->store->logo)
                    : null
            ],
            'messages' => Inertia::merge(
                $this->messages->map(fn ($message) => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'created_at' => $message->created_at?->toIso8601String(),
                    'read_at' => $message->read_at?->toIso8601String(),

                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'avatar' => $message->sender->avatar
                            ? Storage::url($message->sender->avatar)
                            : null,
                    ],

                    'attachments' => $message->attachments->map(fn ($attachment) => [
                        'id' => $attachment->id,
                        'original_name' => $attachment->original_name,
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                    ]),
                ])
            )->append()->matchOn('id'),
        ];
    }
}
