<?php

namespace App\Services\Hr;

use App\Models\JobPosting;
use App\Models\User;

class JobPostingService
{
    public function create(User $creator, array $data, string $slug): JobPosting
    {
        return JobPosting::create([
            ...$data,
            'slug' => $slug,
            'created_by' => $creator->id,
        ]);
    }

    public function update(JobPosting $jobPosting, array $data): JobPosting
    {
        $jobPosting->update($data);

        return $jobPosting;
    }

    public function publish(JobPosting $jobPosting): JobPosting
    {
        $jobPosting->update(['status' => \App\Enums\JobPostingStatus::Published]);

        return $jobPosting;
    }

    public function close(JobPosting $jobPosting): JobPosting
    {
        $jobPosting->update(['status' => \App\Enums\JobPostingStatus::Closed]);

        return $jobPosting;
    }
}