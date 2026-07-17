<?php

namespace App\Http\Requests\Careers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyToJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'applicant_document_id' => [
                'required',
                Rule::exists('applicant_documents', 'id')->where('user_id', $this->user()->id),
            ],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ];
    }
}