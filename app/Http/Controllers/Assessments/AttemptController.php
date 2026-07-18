<?php

namespace App\Http\Controllers\Assessments;

use App\Enums\ViolationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assessments\ReportViolationRequest;
use App\Http\Requests\Assessments\SaveAnswerRequest;
use App\Models\Attempt;
use App\Models\JobApplication;
use App\Services\Assessments\AttemptService;
use App\Services\Assessments\ViolationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttemptController extends Controller
{
    public function __construct(
        private readonly AttemptService $attemptService,
        private readonly ViolationService $violationService,
    ) {}

    public function start(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $this->authorizeApplicant($request, $jobApplication);

        $attempt = $this->attemptService->startOrResume($jobApplication, $request);

        return redirect()->route('applicant.assessments.show', $attempt);
    }

    public function show(Request $request, \App\Models\Attempt $attempt): View
    {
        $this->authorize('view', $attempt);

        if ($attempt->isExpired() && $attempt->status->value === 'in_progress') {
            $this->attemptService->autoSubmit($attempt);
            $attempt->refresh();
        }

        return view('assessments.take', [
            'attempt' => $attempt,
            'questions' => $attempt->orderedQuestions(),
            'existingAnswers' => $attempt->answers()->get()->keyBy('question_id'),
        ]);
    }

    public function saveAnswer(SaveAnswerRequest $request, \App\Models\Attempt $attempt): JsonResponse
    {
        $this->authorize('update', $attempt);

        $this->attemptService->saveAnswer($attempt, $request->validated(), $request->file('file'));

        return response()->json([
            'saved' => true,
            'remaining_seconds' => $attempt->fresh()->remainingSeconds(),
        ]);
    }

    public function submit(Request $request, \App\Models\Attempt $attempt): RedirectResponse
    {
        $this->authorize('update', $attempt);

        $this->attemptService->submit($attempt);

        return redirect()
            ->route('applicant.applications.index')
            ->with('status', 'Assessment submitted successfully.');
    }

    private function authorizeApplicant(Request $request, JobApplication $jobApplication): void
    {
        abort_unless($request->user()->id === $jobApplication->user_id, 403);
    }

    public function reportViolation(ReportViolationRequest $request, Attempt $attempt): JsonResponse
    {
        $this->authorize('update', $attempt);

        $type = ViolationType::from($request->validated('type'));

        $attempt = $this->violationService->record($attempt, $type, $request->validated('metadata'));

        return response()->json([
            'violation_count' => $attempt->violation_count,
            'max_violations_allowed' => $attempt->max_violations_allowed,
            'disqualified' => $attempt->status->value === 'disqualified',
        ]);
    }
}