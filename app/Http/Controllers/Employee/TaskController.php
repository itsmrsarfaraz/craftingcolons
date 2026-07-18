<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreTaskRequest;
use App\Http\Requests\Employee\UpdateTaskStatusRequest;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Services\Employee\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(Request $request): View
    {
        $tasks = $request->user()->employee->tasks()->with('reports')->latest()->paginate(15);

        return view('employee.tasks.index', compact('tasks'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->create($request->user()->employee, $request->validated());

        return back()->with('status', 'Task created.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->taskService->updateStatus($task, TaskStatus::from($request->validated('status')));

        return back()->with('status', 'Task status updated.');
    }
}