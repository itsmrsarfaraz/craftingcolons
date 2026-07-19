<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Traits\HasMediaCollection;
use App\Traits\HasSeoMeta;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, HasTags, HasMediaCollection, HasSeoMeta;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'project_type' => ProjectType::class,
            'status' => ProjectStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ProjectResult::class)->orderBy('order');
    }

    public function isPublished(): bool
    {
        return $this->status === ProjectStatus::Published
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', ProjectStatus::Published)
            ->where('published_at', '<=', now());
    }
}