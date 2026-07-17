<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-assessments');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'instructions' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'passing_marks' => ['required', 'integer', 'min:0', 'max:100'],
            'shuffle_questions' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'shuffle_questions' => $this->boolean('shuffle_questions'),
        ]);
    }
}