<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\Hr\EmployeeManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(private readonly EmployeeManagementService $employeeManagementService)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->can('manage-employees'), 403);

        $employees = Employee::query()
            ->with('user', 'manager')
            ->when($request->query('department'), fn ($q, $dept) => $q->where('department', $dept))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest('joined_at')
            ->paginate(20)
            ->withQueryString();

        $departments = Employee::query()->whereNotNull('department')->distinct()->pluck('department');

        return view('hr.employees.index', compact('employees', 'departments'));
    }

    public function edit(Request $request, Employee $employee): View
    {
        abort_unless($request->user()->can('manage-employees'), 403);

        $employee->load('user', 'manager');

        return view('hr.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->employeeManagementService->update($employee, $request->validated());

        return redirect()->route('hr.employees.index')->with('status', "{$employee->user->name}'s record updated.");
    }
}