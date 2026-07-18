<?php

namespace App\Services\Employee;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function clockIn(Employee $employee): Attendance
    {
        $today = Carbon::today();

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'attendance' => 'You have already clocked in today.',
            ]);
        }

        $now = Carbon::now();
        $workStart = Carbon::parse(config('attendance.work_start_time'));
        $graceMinutes = config('attendance.late_grace_minutes');

        $isLate = $now->format('H:i') > $workStart->clone()->addMinutes($graceMinutes)->format('H:i');

        return Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'clock_in' => $now,
            'status' => $isLate ? AttendanceStatus::Late : AttendanceStatus::Present,
        ]);
    }

    public function clockOut(Employee $employee): Attendance
    {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (! $attendance) {
            throw ValidationException::withMessages([
                'attendance' => 'You have not clocked in today.',
            ]);
        }

        if ($attendance->clock_out) {
            throw ValidationException::withMessages([
                'attendance' => 'You have already clocked out today.',
            ]);
        }

        $attendance->update(['clock_out' => Carbon::now()]);

        $hoursThreshold = config('attendance.half_day_hours_threshold');
        if ($attendance->hoursWorked() < $hoursThreshold && $attendance->status !== AttendanceStatus::Late) {
            $attendance->update(['status' => AttendanceStatus::HalfDay]);
        }

        return $attendance->fresh();
    }
}