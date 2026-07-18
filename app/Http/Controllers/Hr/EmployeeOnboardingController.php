<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\OnboardEmployeeRequest;
use App\Models\Employee;
use App\Models\JobApplication;
use App\Services\Hr\EmployeeOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeOnboardingController extends Controller
{
    public function __construct(private readonly EmployeeOnboardingService $onboardingService)
    {
    }

    public function create(JobApplication $application): View
    {
        $this->authorize('create', Employee::class);

        return view('hr.onboarding.create', compact('application'));
    }

    public function store(OnboardEmployeeRequest $request, JobApplication $application): RedirectResponse
    {
        $employee = $this->onboardingService->onboard($application, $request->validated());

        return redirect()
            ->route('hr.applications.show', $application)
            ->with('status', "{$application->applicant->name} onboarded as {$employee->employee_code}.");
    }
}