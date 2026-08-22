<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'message_id',
    'disk',
    'path',
    'original_name',
    'mime_type',
    'size',
])]
class MessageAttachment extends Model
{
    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    protected $appends = [
        'url',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }
        
        /** @var Filesystem $disk */
        $disk = Storage::disk($this->disk);
        return $disk->url($this->path);
    }
}
