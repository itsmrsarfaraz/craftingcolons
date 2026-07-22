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
            // Only validate rows that actually have a non-blank label —
            // blank rows are just unused slots in the option pool, not
            // errors. This is what lets HR use 2, 3, 5, or 6 options
            // without a fixed count.
            $rules['options'] = ['required', 'array'];
            $rules['options.*.label'] = ['nullable', 'string', 'max:500'];
            $rules['options.*.is_correct'] = ['boolean'];
        }

        return $rules;
    }

    /**
     * Cross-field rules array validation can't express:
     * - At least 2 non-blank options overall.
     * - Exactly one correct option for MCQ/True-False, at least one for Multiple Select.
     * - True/False must have exactly 2 non-blank options.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = QuestionType::tryFrom((string) $this->input('type'));

            if (! $type?->usesOptions()) {
                return;
            }

            $filledOptions = collect($this->input('options', []))
                ->filter(fn ($option) => filled($option['label'] ?? null))
                ->values();

            if ($filledOptions->count() < 2) {
                $validator->errors()->add('options', 'At least 2 options are required.');

                return;
            }

            if ($type === QuestionType::TrueFalse && $filledOptions->count() !== 2) {
                $validator->errors()->add('options', 'True/False questions must have exactly 2 options.');

                return;
            }

            $correctCount = $filledOptions->filter(fn ($option) => (bool) ($option['is_correct'] ?? false))->count();

            if ($correctCount === 0) {
                $validator->errors()->add('options', 'At least one option must be marked correct.');
            }

            if (in_array($type, [QuestionType::Mcq, QuestionType::TrueFalse]) && $correctCount > 1) {
                $validator->errors()->add('options', 'This question type allows only one correct option.');
            }
        });
    }
}