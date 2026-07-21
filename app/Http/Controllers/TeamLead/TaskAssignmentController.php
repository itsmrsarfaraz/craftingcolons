<?php

namespace App\Http\Controllers\TeamLead;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamLead\AssignTaskRequest;
use App\Models\Employee;
use App\Notifications\TaskAssignedNotification;
use App\Services\Employee\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskAssignmentController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function create(Request $request): View
    {
        $teamMembers = Employee::where('reports_to', $request->user()->id)->with('user')->get();

        return view('team-lead.tasks.assign', compact('teamMembers'));
    }

    public function store(AssignTaskRequest $request): RedirectResponse
    {
        $employee = Employee::findOrFail($request->validated('employee_id'));

        $task = $this->taskService->create(
            $employee,
            $request->validated(),
            $request->user()->id
        );

        $employee->user->notify(new TaskAssignedNotification($task));

        return redirect()->route('team-lead.tasks.assign')->with('status', "Task assigned to {$employee->user->name}.");
    }
}