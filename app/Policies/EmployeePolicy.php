<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-employees');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->id === $employee->user_id
            || $user->id === $employee->reports_to
            || $user->can('manage-employees');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-employees');
    }
}