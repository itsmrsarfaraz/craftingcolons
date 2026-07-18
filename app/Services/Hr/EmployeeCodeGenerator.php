<?php

namespace App\Services\Hr;

use App\Models\Employee;

class EmployeeCodeGenerator
{
    public function next(): string
    {
        $lastNumber = Employee::query()
            ->selectRaw("MAX(CAST(SUBSTRING(employee_code, 4) AS UNSIGNED)) as max_number")
            ->value('max_number');

        return 'CC-'.str_pad((int) $lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}