<?php

namespace App\Http\Controllers\Hr;

use App\Enums\JobApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\UpdateApplicationStatusRequest;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\Hr\JobApplicationStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct(private readonly JobApplicationStatusService $statusService)
    {
    }

    public function index(JobPosting $jobPosting): View
    {
        $this->authorize('viewAny', JobApplication::class);

        $applications = $jobPosting->applications()
            ->with('applicant', 'document', 'attempt')
            ->latest('applied_at')
            ->paginate(20);

        return view('hr.applications.index', compact('jobPosting', 'applications'));
    }

    public function show(JobApplication $application): View
    {
        $this->authorize('view', $application);

        $application->load('applicant.applicantProfile', 'document', 'attempt.violations', 'jobPosting');

        return view('hr.applications.show', compact('application'));
    }

    public function all(Request $request): View
    {
        $applications = \App\Models\JobApplication::query()
            ->with('applicant', 'jobPosting', 'attempt')
            ->latest('applied_at')
            ->paginate(20);

        return view('hr.applications.all', compact('applications'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, JobApplication $application): RedirectResponse
    {
        $this->authorize('updateStatus', $application);

        $this->statusService->transition(
            $application,
            JobApplicationStatus::from($request->validated('status'))
        );

        return back()->with('status', 'Application status updated.');
    }
}