<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => AttendanceStatus::class,
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function hoursWorked(): float
    {
        if (! $this->clock_in || ! $this->clock_out) {
            return 0.0;
        }

        return round($this->clock_in->diffInMinutes($this->clock_out) / 60, 2);
    }
}