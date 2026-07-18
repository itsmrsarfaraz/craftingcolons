<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreTaskReportRequest;
use App\Models\Task;
use App\Services\Employee\TaskReportService;
use Illuminate\Http\RedirectResponse;

class TaskReportController extends Controller
{
    public function __construct(private readonly TaskReportService $reportService)
    {
    }

    public function store(StoreTaskReportRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->reportService->submitToday($task, $request->validated('summary'), $request->file('evidence'));

        return back()->with('status', 'Daily report submitted.');
    }
}