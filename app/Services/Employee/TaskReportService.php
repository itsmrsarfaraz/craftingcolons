<?php

namespace App\Services\Employee;

use App\Models\Task;
use App\Models\TaskReport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class TaskReportService
{
    public function submitToday(Task $task, string $summary, ?UploadedFile $evidence = null): TaskReport
    {
        $path = $evidence?->store("tasks/{$task->id}/evidence", 'local');

        return $task->reports()->updateOrCreate(
            ['report_date' => Carbon::today()],
            ['summary' => $summary, 'evidence_path' => $path]
        );
    }
}