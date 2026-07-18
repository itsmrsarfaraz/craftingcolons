<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => JobApplicationStatus::class,
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocument::class, 'applicant_document_id');
    }

    public function attempt(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Attempt::class);
    }
}