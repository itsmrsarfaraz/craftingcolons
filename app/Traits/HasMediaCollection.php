<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMediaCollection
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function featuredImage(): ?Media
    {
        return $this->media()->where('collection', 'featured')->first();
    }
}