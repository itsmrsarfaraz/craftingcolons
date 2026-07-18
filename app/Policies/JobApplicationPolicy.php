<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-applications') || $user->can('review-candidates');
    }

    public function view(User $user, JobApplication $application): bool
    {
        return $user->id === $application->user_id
            || $user->can('manage-applications')
            || $user->can('review-candidates');
    }

    public function updateStatus(User $user, JobApplication $application): bool
    {
        return $user->can('manage-applications') || $user->can('review-candidates');
    }
}