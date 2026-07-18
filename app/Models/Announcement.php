<?php

namespace App\Models;

use App\Enums\AnnouncementAudience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'audience' => AnnouncementAudience::class,
            'published_at' => 'datetime',
        ];
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }
}