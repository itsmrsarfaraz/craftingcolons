<?php

namespace App\Http\Controllers\Careers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Careers\ApplyToJobRequest;
use App\Models\JobPosting;
use App\Services\Careers\JobApplicationService;
use Illuminate\Http\RedirectResponse;

class JobApplicationController extends Controller
{
    public function __construct(private readonly JobApplicationService $applicationService)
    {
    }

    public function store(ApplyToJobRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $this->applicationService->apply($request->user(), $jobPosting, $request->validated());

        return redirect()
            ->route('applicant.applications.index')
            ->with('status', "Application submitted for \"{$jobPosting->title}\".");
    }
}