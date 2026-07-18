<?php

namespace App\Models;

use App\Enums\NewsStatus;
use App\Traits\HasCategories;
use App\Traits\HasMediaCollection;
use App\Traits\HasSeoMeta;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory, HasCategories, HasTags, HasMediaCollection, HasSeoMeta;

    protected $table = 'news';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => NewsStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isPublished(): bool
    {
        return $this->status === NewsStatus::Published
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', NewsStatus::Published)
            ->where('published_at', '<=', now());
    }
}