<?php

return [
    'work_start_time' => env('ATTENDANCE_WORK_START', '09:00'),
    'late_grace_minutes' => env('ATTENDANCE_LATE_GRACE_MINUTES', 15),
    'half_day_hours_threshold' => env('ATTENDANCE_HALF_DAY_HOURS', 4),
];