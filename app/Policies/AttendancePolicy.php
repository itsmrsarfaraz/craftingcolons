<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->employee !== null || $user->can('manage-employees');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->id === $attendance->employee->user_id
            || $user->id === $attendance->employee->reports_to
            || $user->can('manage-employees');
    }
}