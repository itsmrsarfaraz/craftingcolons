<?php

namespace App\Observers;

use App\Models\JobPosting;
use App\Services\Audit\ActivityLogger;

class JobPostingObserver
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function created(JobPosting $jobPosting): void
    {
        $this->logger->log('created', "Job posting \"{$jobPosting->title}\" created", $jobPosting);
    }

    public function updated(JobPosting $jobPosting): void
    {
        if ($jobPosting->wasChanged('status')) {
            $this->logger->log(
                'status_changed',
                "Job posting \"{$jobPosting->title}\" status changed to {$jobPosting->status->label()}",
                $jobPosting,
                ['from' => $jobPosting->getOriginal('status'), 'to' => $jobPosting->status->value]
            );
        }
    }
}