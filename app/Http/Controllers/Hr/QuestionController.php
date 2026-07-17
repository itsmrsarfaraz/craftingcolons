<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreQuestionRequest;
use App\Models\Assessment;
use App\Models\Question;
use App\Services\Hr\QuestionService;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    public function store(StoreQuestionRequest $request, Assessment $assessment): RedirectResponse
    {
        $this->authorize('update', $assessment);

        $this->questionService->create($assessment, $request->validated());

        return back()->with('status', 'Question added.');
    }

    public function update(StoreQuestionRequest $request, Assessment $assessment, Question $question): RedirectResponse
    {
        $this->authorize('update', $assessment);

        $this->questionService->update($question, $request->validated());

        return back()->with('status', 'Question updated.');
    }

    public function destroy(Assessment $assessment, Question $question): RedirectResponse
    {
        $this->authorize('update', $assessment);

        $this->questionService->delete($question);

        return back()->with('status', 'Question removed.');
    }
}