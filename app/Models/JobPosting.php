<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'status' => JobPostingStatus::class,
            'assessment_required' => 'boolean',
            'deadline' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function isOpen(): bool
    {
        return $this->status === JobPostingStatus::Published
            && (! $this->deadline || $this->deadline->isFuture());
    }

    public function assessment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Assessment::class);
    }
}