<?php

namespace App\Services\Hr;

use App\Models\Assessment;
use App\Models\JobPosting;
use App\Models\User;

class AssessmentService
{
    public function create(User $creator, JobPosting $jobPosting, array $data): Assessment
    {
        return Assessment::create([
            ...$data,
            'job_posting_id' => $jobPosting->id,
            'created_by' => $creator->id,
        ]);
    }

    public function update(Assessment $assessment, array $data): Assessment
    {
        $assessment->update($data);

        return $assessment;
    }
}