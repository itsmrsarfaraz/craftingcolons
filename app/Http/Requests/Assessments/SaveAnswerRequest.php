<?php

namespace App\Http\Requests\Assessments;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer'],
            'selected_option_ids' => ['nullable', 'array'],
            'selected_option_ids.*' => ['integer'],
            'text_answer' => ['nullable', 'string', 'max:10000'],
            'file' => ['nullable', 'file', 'max:10240'], // 10MB, for file_upload/coding types
        ];
    }
}