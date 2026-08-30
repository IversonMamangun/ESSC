<?php

namespace App\Http\Resources\Seller;

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
            'store_unread_count' => $this->store_unread_count,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'last_message' => $this->relationLoaded('latestMessage')
                ? ($this->latestMessage?->body
                    ? Str::limit($this->latestMessage->body, 40)
                    : ($this->latestMessage?->attachments->isNotEmpty()
                        ? 'Sent an attachment'
                        : null))
                : null,
            'user' => [
                'name' => $this->user->name,
                'avatar' => $this->user->avatar
                    ? Storage::url($this->user->avatar)
                    : null
            ]
        ];
    }
}
