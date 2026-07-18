<?php

namespace App\Services\Employee;

use App\Enums\TaskStatus;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function create(Employee $employee, array $data, ?int $assignedBy = null): Task
    {
        return $employee->tasks()->create([
            ...$data,
            'assigned_by' => $assignedBy,
        ]);
    }

    /**
     * Employee-driven status change. Enforces the transition map on the
     * enum — an employee cannot jump straight to "Completed" themselves.
     */
    public function updateStatus(Task $task, TaskStatus $next): Task
    {
        if (! in_array($next, $task->status->allowedNextStatuses(), true)) {
            throw ValidationException::withMessages([
                'status' => "Cannot move from \"{$task->status->label()}\" to \"{$next->label()}\".",
            ]);
        }

        $task->update(['status' => $next]);

        return $task->fresh();
    }

    /**
     * Team Lead / HR-only action: approve a task out of Review into Completed.
     */
    public function approve(Task $task, int $reviewerId): Task
    {
        if ($task->status !== TaskStatus::Review) {
            throw ValidationException::withMessages([
                'status' => 'Only tasks awaiting review can be approved.',
            ]);
        }

        $task->update([
            'status' => TaskStatus::Completed,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        return $task->fresh();
    }

    /**
     * Team Lead sends a task back for more work instead of approving it.
     */
    public function requestChanges(Task $task, int $reviewerId): Task
    {
        if ($task->status !== TaskStatus::Review) {
            throw ValidationException::withMessages([
                'status' => 'Only tasks awaiting review can be sent back.',
            ]);
        }

        $task->update([
            'status' => TaskStatus::InProgress,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        return $task->fresh();
    }
}