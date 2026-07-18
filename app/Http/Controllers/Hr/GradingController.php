<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\GradeAnswersRequest;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\JobPosting;
use App\Services\Assessments\GradingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradingController extends Controller
{
    public function __construct(private readonly GradingService $gradingService)
    {
    }

    /**
     * Ranked list of attempts for a job posting — the "Ranking" and
     * "Score Calculation" bullets from the spec's Evaluation section.
     */
    public function ranking(JobPosting $jobPosting): View
    {
        $this->authorize('viewAny', \App\Models\JobPosting::class);

        $attempts = Attempt::query()
            ->whereHas('jobApplication', fn ($q) => $q->where('job_posting_id', $jobPosting->id))
            ->with('candidate')
            ->orderByRaw('score IS NULL, score DESC')
            ->get();

        return view('hr.grading.ranking', compact('jobPosting', 'attempts'));
    }

    public function show(Attempt $attempt): View
    {
        $this->authorize('grade', $attempt);

        $attempt->load('answers.question.options', 'candidate', 'assessment');

        return view('hr.grading.show', compact('attempt'));
    }

    public function store(GradeAnswersRequest $request, Attempt $attempt): RedirectResponse
    {
        $this->authorize('grade', $attempt);

        foreach ($request->validated('grades') as $grade) {
            $answer = AttemptAnswer::findOrFail($grade['answer_id']);

            abort_unless($answer->attempt_id === $attempt->id, 403);

            $this->gradingService->gradeManualAnswer($answer, $grade['marks_awarded']);
        }

        return redirect()
            ->route('hr.grading.ranking', $attempt->jobApplication->job_posting_id)
            ->with('status', 'Grades saved.');
    }
}