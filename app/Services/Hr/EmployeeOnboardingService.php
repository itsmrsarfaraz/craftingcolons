<?php

namespace App\Services\Hr;

use App\Enums\JobApplicationStatus;
use App\Models\Employee;
use App\Models\JobApplication;
use Illuminate\Validation\ValidationException;

class EmployeeOnboardingService
{
    public function __construct(private readonly EmployeeCodeGenerator $codeGenerator)
    {
    }

    public function onboard(JobApplication $application, array $data): Employee
    {
        if ($application->status !== JobApplicationStatus::Hired) {
            throw ValidationException::withMessages([
                'application' => 'Only hired applicants can be onboarded.',
            ]);
        }

        if ($application->user->employee) {
            throw ValidationException::withMessages([
                'application' => 'This user has already been onboarded.',
            ]);
        }

        $employee = Employee::create([
            ...$data,
            'user_id' => $application->user_id,
            'job_application_id' => $application->id,
            'employee_code' => $this->codeGenerator->next(),
        ]);

        $roleSlug = $data['employment_type'] === 'internship' ? 'intern' : 'employee';
        $application->user->assignRole($roleSlug);

        return $employee;
    }
}