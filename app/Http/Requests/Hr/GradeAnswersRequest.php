<?php

namespace App\Http\Requests\Hr;

use App\Models\AttemptAnswer;
use Illuminate\Foundation\Http\FormRequest;

class GradeAnswersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-assessments');
    }

    public function rules(): array
    {
        return [
            'grades' => ['required', 'array', 'min:1'],
            'grades.*.answer_id' => ['required', 'integer', 'exists:attempt_answers,id'],
            'grades.*.marks_awarded' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Cross-field rule array validation can't express: marks_awarded can
     * never exceed the question's own max marks.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->input('grades', []) as $index => $grade) {
                $answer = AttemptAnswer::with('question')->find($grade['answer_id'] ?? null);

                if ($answer && ($grade['marks_awarded'] ?? 0) > $answer->question->marks) {
                    $validator->errors()->add(
                        "grades.{$index}.marks_awarded",
                        "Cannot exceed the question's maximum of {$answer->question->marks} marks."
                    );
                }
            }
        });
    }
}