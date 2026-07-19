<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreAssessmentRequest;
use App\Models\Assessment;
use App\Models\JobPosting;
use App\Services\Hr\AssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly \App\Services\Settings\SettingsService $settingsService,
        )
    {
    }

    public function create(JobPosting $jobPosting): View
    {
        $this->authorize('create', Assessment::class);

        $defaultMaxViolations = $this->settingsService->get('assessment.max_violations_allowed_default', 3);

        return view('hr.assessments.create', compact('jobPosting', 'defaultMaxViolations'));
    }

    public function store(StoreAssessmentRequest $request, JobPosting $jobPosting): RedirectResponse
    {
        $assessment = $this->assessmentService->create($request->user(), $jobPosting, $request->validated());

        return redirect()
            ->route('hr.assessments.edit', $assessment)
            ->with('status', 'Assessment created. Now add your questions.');
    }

    public function edit(Assessment $assessment): View
    {
        $this->authorize('view', $assessment);

        $assessment->load('questions.options');

        return view('hr.assessments.edit', compact('assessment'));
    }
}