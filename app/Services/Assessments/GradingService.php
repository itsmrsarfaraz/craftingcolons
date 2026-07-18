<?php

namespace App\Services\Assessments;

use App\Models\Attempt;
use App\Models\AttemptAnswer;

class GradingService
{
    /**
     * Ensure every question in the assessment has an answer row (unanswered
     * questions count as zero, they're never silently excluded from the total)
     * and immediately grade every auto-gradable question.
     * Called right after an attempt transitions to a final status.
     */
    public function gradeOnSubmission(Attempt $attempt): Attempt
    {
        $this->ensureAnswerRowsExist($attempt);
        $this->gradeAutoGradableAnswers($attempt);

        return $this->finalizeIfComplete($attempt);
    }

    /**
     * HR manually awards marks for a non-auto-gradable answer
     * (short answer, long answer, file upload, coding).
     */
    public function gradeManualAnswer(AttemptAnswer $answer, int $marksAwarded): Attempt
    {
        $answer->update(['marks_awarded' => $marksAwarded]);

        return $this->finalizeIfComplete($answer->attempt);
    }

    private function ensureAnswerRowsExist(Attempt $attempt): void
    {
        $existingQuestionIds = $attempt->answers()->pluck('question_id');
        $missingQuestionIds = collect($attempt->question_order)->diff($existingQuestionIds);

        foreach ($missingQuestionIds as $questionId) {
            $attempt->answers()->create(['question_id' => $questionId]);
        }
    }

    private function gradeAutoGradableAnswers(Attempt $attempt): void
    {
        $questions = $attempt->assessment->questions()->with('options')->get()->keyBy('id');

        $attempt->answers()
            ->whereNull('marks_awarded')
            ->get()
            ->each(function (AttemptAnswer $answer) use ($questions) {
                $question = $questions->get($answer->question_id);

                if (! $question || ! $question->type->isAutoGradable()) {
                    return; // manual question — leave marks_awarded null for HR
                }

                $correctOptionIds = $question->options
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->sort()
                    ->values();

                $selectedOptionIds = collect($answer->selected_option_ids ?? [])
                    ->sort()
                    ->values();

                $isCorrect = $correctOptionIds->all() === $selectedOptionIds->all();

                $answer->update(['marks_awarded' => $isCorrect ? $question->marks : 0]);
            });
    }

    /**
     * Compute final score/passed only once every question has a mark.
     * If any manual question is still ungraded, the attempt stays
     * unfinalized — graded_at remains null, signaling "pending HR review."
     */
    private function finalizeIfComplete(Attempt $attempt): Attempt
    {
        $stillUngraded = $attempt->answers()->whereNull('marks_awarded')->exists();

        if ($stillUngraded) {
            return $attempt->fresh();
        }

        $totalMarks = max(1, $attempt->assessment->total_marks);
        $earnedMarks = (int) $attempt->answers()->sum('marks_awarded');
        $score = (int) round(($earnedMarks / $totalMarks) * 100);

        $attempt->update([
            'score' => $score,
            'passed' => $score >= $attempt->assessment->passing_marks,
            'graded_at' => now(),
        ]);

        return $attempt->fresh();
    }
}