<?php

namespace App\Services\Careers;

use App\Enums\JobPostingStatus;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class JobApplicationService
{
    public function apply(User $user, JobPosting $jobPosting, array $data): JobApplication
    {
        if (! $jobPosting->isOpen()) {
            throw ValidationException::withMessages([
                'job_posting' => 'This position is no longer accepting applications.',
            ]);
        }

        if ($jobPosting->applications()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'job_posting' => 'You have already applied to this position.',
            ]);
        }

        return $jobPosting->applications()->create([
            'user_id' => $user->id,
            'applicant_document_id' => $data['applicant_document_id'],
            'cover_letter' => $data['cover_letter'] ?? null,
        ]);
    }
}