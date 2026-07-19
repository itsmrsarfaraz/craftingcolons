<?php

namespace App\Observers;

use App\Models\Employee;
use App\Services\Audit\ActivityLogger;

class EmployeeObserver
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function created(Employee $employee): void
    {
        $this->logger->log('created', "Employee {$employee->employee_code} onboarded", $employee);
    }

    public function updated(Employee $employee): void
    {
        if ($employee->wasChanged('status')) {
            $this->logger->log(
                'status_changed',
                "Employee {$employee->employee_code} status changed to {$employee->status->label()}",
                $employee,
                ['from' => $employee->getOriginal('status'), 'to' => $employee->status->value]
            );
        }
    }
}