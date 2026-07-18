<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'present';
    case Late = 'late';
    case HalfDay = 'half_day';
    case Absent = 'absent';
    case OnLeave = 'on_leave';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::Late => 'Late',
            self::HalfDay => 'Half Day',
            self::Absent => 'Absent',
            self::OnLeave => 'On Leave',
        };
    }
}