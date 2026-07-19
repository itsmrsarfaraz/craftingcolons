<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use App\Traits\HasMediaCollection;
use App\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory, HasMediaCollection, HasSeoMeta;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => ServiceStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isPublished(): bool
    {
        return $this->status === ServiceStatus::Published
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', ServiceStatus::Published)
            ->where('published_at', '<=', now())
            ->orderBy('order');
    }
}