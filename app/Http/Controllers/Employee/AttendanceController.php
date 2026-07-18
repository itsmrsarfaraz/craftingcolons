<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index(Request $request): View
    {
        $employee = $request->user()->employee;

        $attendances = $employee->attendances()->latest('date')->paginate(15);
        $today = $employee->attendances()->whereDate('date', now())->first();

        return view('employee.attendance.index', compact('attendances', 'today'));
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $this->attendanceService->clockIn($request->user()->employee);

        return back()->with('status', 'Clocked in successfully.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $this->attendanceService->clockOut($request->user()->employee);

        return back()->with('status', 'Clocked out successfully.');
    }
}