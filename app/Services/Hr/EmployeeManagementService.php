<?php

namespace App\Services\Hr;

use App\Models\Employee;

class EmployeeManagementService
{
    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee->fresh();
    }
}