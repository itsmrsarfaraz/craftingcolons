<?php

namespace App\Http\Requests\Hr;

use App\Enums\QuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-assessments');
    }

    public function rules(): array
    {
        $type = QuestionType::tryFrom((string) $this->input('type'));

        $rules = [
            'type' => ['required', Rule::enum(QuestionType::class)],
            'prompt' => ['required', 'string'],
            'marks' => ['required', 'integer', 'min:1', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'language' => ['nullable', 'string', 'max:50', 'required_if:type,coding'],
        ];

        if ($type?->usesOptions()) {
            $rules['options'] = ['required', 'array', 'min:2'];
            $rules['options.*.label'] = ['required', 'string', 'max:500'];
            $rules['options.*.is_correct'] = ['boolean'];
        }

        return $rules;
    }

    /**
     * Cross-field rule that array-shape validation can't express:
     * MCQ/True-False need exactly one correct option; Multiple Select needs at least one.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = QuestionType::tryFrom((string) $this->input('type'));

            if (! $type?->usesOptions()) {
                return;
            }

            $correctCount = collect($this->input('options', []))
                ->filter(fn ($option) => (bool) ($option['is_correct'] ?? false))
                ->count();

            if ($correctCount === 0) {
                $validator->errors()->add('options', 'At least one option must be marked correct.');
            }

            if (in_array($type, [QuestionType::Mcq, QuestionType::TrueFalse]) && $correctCount > 1) {
                $validator->errors()->add('options', 'This question type allows only one correct option.');
            }
        });
    }
}