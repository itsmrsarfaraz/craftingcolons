<?php

namespace App\Models;

use App\Enums\ViolationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => ViolationType::class,
            'occurred_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }
}