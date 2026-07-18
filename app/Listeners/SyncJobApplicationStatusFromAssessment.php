<?php

namespace App\Listeners;

use App\Enums\JobApplicationStatus;
use App\Events\AssessmentGraded;
use App\Services\Hr\JobApplicationStatusService;

class SyncJobApplicationStatusFromAssessment
{
    public function __construct(private readonly JobApplicationStatusService $statusService)
    {
    }

    /**
     * Auto-advance the application only if it's still untouched ("applied").
     * If HR already moved it manually for any reason, automation backs off —
     * a human decision always outranks the automatic sync.
     */
    public function handle(AssessmentGraded $event): void
    {
        $application = $event->attempt->jobApplication;

        if ($application->status !== JobApplicationStatus::Applied) {
            return;
        }

        $nextStatus = $event->attempt->passed
            ? JobApplicationStatus::Shortlisted
            : JobApplicationStatus::Rejected;

        $this->statusService->transition($application, $nextStatus);
    }
}