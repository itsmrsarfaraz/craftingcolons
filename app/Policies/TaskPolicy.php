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
     * A Team Lead can only review tasks for employees who report directly
     * to them — having the "review-tasks" permission grants the *ability*
     * to review tasks in general, not a blanket pass over every employee
     * in the company. Only HR/Admin get that unscoped override, since they
     * legitimately oversee all teams.
     */
    public function review(User $user, Task $task): bool
    {
        return $user->id === $task->employee->reports_to
            || $user->hasAnyRole(['hr', 'admin']);
    }
}