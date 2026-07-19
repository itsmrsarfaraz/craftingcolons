<?php

namespace App\Observers;

use App\Models\JobApplication;
use App\Services\Audit\ActivityLogger;

class JobApplicationObserver
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function updated(JobApplication $application): void
    {
        if ($application->wasChanged('status')) {
            $this->logger->log(
                'status_changed',
                "Application #{$application->id} status changed to {$application->status->label()}",
                $application,
                ['from' => $application->getOriginal('status'), 'to' => $application->status->value]
            );
        }
    }
}