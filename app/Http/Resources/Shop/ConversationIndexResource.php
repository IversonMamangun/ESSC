<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConversationIndexResource extends JsonResource
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
            'user_unread_count' => $this->user_unread_count,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'last_message' => $this->relationLoaded('latestMessage')
                ? ($this->latestMessage?->body
                    ? Str::limit($this->latestMessage->body, 40)
                    : ($this->latestMessage?->attachments->isNotEmpty()
                        ? 'Sent an attachment'
                        : null))
                : null,
            'store' => [
                'name' => $this->store->name,
                'logo' => $this->store->logo
                    ? Storage::url($this->store->logo)
                    : null
            ]
        ];
    }
}
