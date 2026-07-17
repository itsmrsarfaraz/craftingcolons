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
    public function __construct(private readonly AssessmentService $assessmentService)
    {
    }

    public function create(JobPosting $jobPosting): View
    {
        $this->authorize('create', Assessment::class);

        return view('hr.assessments.create', compact('jobPosting'));
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