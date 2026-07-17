<?php

namespace App\Services\Hr;

use App\Enums\QuestionType;
use App\Models\Assessment;
use App\Models\Question;

class QuestionService
{
    public function create(Assessment $assessment, array $data): Question
    {
        $question = $assessment->questions()->create([
            'type' => $data['type'],
            'prompt' => $data['prompt'],
            'marks' => $data['marks'],
            'order' => $data['order'] ?? ($assessment->questions()->max('order') + 1),
            'language' => $data['language'] ?? null,
        ]);

        if (QuestionType::from($data['type'])->usesOptions()) {
            $this->syncOptions($question, $data['options']);
        }

        $assessment->recalculateTotalMarks();

        return $question;
    }

    public function update(Question $question, array $data): Question
    {
        $question->update([
            'prompt' => $data['prompt'],
            'marks' => $data['marks'],
            'order' => $data['order'] ?? $question->order,
            'language' => $data['language'] ?? null,
        ]);

        if ($question->type->usesOptions() && isset($data['options'])) {
            $question->options()->delete();
            $this->syncOptions($question, $data['options']);
        }

        $question->assessment->recalculateTotalMarks();

        return $question;
    }

    public function delete(Question $question): void
    {
        $assessment = $question->assessment;
        $question->delete();
        $assessment->recalculateTotalMarks();
    }

    private function syncOptions(Question $question, array $options): void
    {
        foreach ($options as $index => $option) {
            $question->options()->create([
                'label' => $option['label'],
                'is_correct' => (bool) ($option['is_correct'] ?? false),
                'order' => $index,
            ]);
        }
    }
}