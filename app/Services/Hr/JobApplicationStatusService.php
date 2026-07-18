<?php

namespace App\Services\Hr;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use Illuminate\Validation\ValidationException;

class JobApplicationStatusService
{
    public function transition(JobApplication $application, JobApplicationStatus $next): JobApplication
    {
        if (! $application->status->canTransitionTo($next)) {
            throw ValidationException::withMessages([
                'status' => "Cannot move from \"{$application->status->label()}\" to \"{$next->label()}\".",
            ]);
        }

        $application->update([
            'status' => $next,
            'reviewed_at' => now(),
        ]);

        return $application->fresh();
    }
}