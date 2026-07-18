<?php

namespace App\Http\Controllers\TeamLead;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\Employee\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskReviewController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::query()
            ->where('status', 'review')
            ->whereHas('employee', fn ($q) => $q->where('reports_to', $request->user()->id))
            ->with('employee.user', 'reports')
            ->latest()
            ->paginate(15);

        return view('team-lead.tasks.review', compact('tasks'));
    }

    public function approve(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('review', $task);

        $this->taskService->approve($task, $request->user()->id);

        return back()->with('status', 'Task approved.');
    }

    public function requestChanges(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('review', $task);

        $this->taskService->requestChanges($task, $request->user()->id);

        return back()->with('status', 'Sent back for changes.');
    }
}