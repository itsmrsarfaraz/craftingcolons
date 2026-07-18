<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Traits\HasCategories;
use App\Traits\HasMediaCollection;
use App\Traits\HasSeoMeta;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory, HasCategories, HasTags, HasMediaCollection, HasSeoMeta;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::Published
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::Published)
            ->where('published_at', '<=', now());
    }
}