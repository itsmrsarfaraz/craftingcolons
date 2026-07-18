<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Attempt extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => AttemptStatus::class,
            'question_order' => 'array',
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    public function remainingSeconds(): int
    {
        return max(0, (int) Carbon::now()->diffInSeconds($this->expires_at, false));
    }

    /**
     * Ordered question models matching the snapshotted question_order.
     */
    public function orderedQuestions(): \Illuminate\Support\Collection
    {
        $questions = $this->assessment->questions()->with('options')->get()->keyBy('id');

        return collect($this->question_order)->map(fn ($id) => $questions->get($id))->filter()->values();
    }
}