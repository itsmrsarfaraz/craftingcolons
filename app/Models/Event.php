<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Traits\HasCategories;
use App\Traits\HasMediaCollection;
use App\Traits\HasSeoMeta;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, HasCategories, HasTags, HasMediaCollection, HasSeoMeta;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => EventStatus::class,
            'is_virtual' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at->isFuture();
    }

    public function isFull(): bool
    {
        if ($this->max_attendees === null) {
            return false;
        }

        return $this->registrations()->where('status', 'registered')->count() >= $this->max_attendees;
    }

    public function scopePublished($query)
    {
        return $query->where('status', EventStatus::Published);
    }
}