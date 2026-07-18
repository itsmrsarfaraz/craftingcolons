<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['team-lead', 'hr', 'admin']);
    }
    
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->employee->user_id
            || $user->id === $task->employee->reports_to
            || $user->hasAnyRole(['team-lead', 'hr', 'admin']);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->employee->user_id;
    }

    /**
     * Only the assignee's own manager, or HR/Admin, can approve/reject —
     * not just any Team Lead in the company.
     */
    public function review(User $user, Task $task): bool
    {
        return $user->id === $task->employee->reports_to
            || $user->can('review-tasks')
            || $user->hasAnyRole(['hr', 'admin']);
    }
}