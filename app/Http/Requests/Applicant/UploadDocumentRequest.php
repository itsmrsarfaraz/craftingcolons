<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(\App\Enums\DocumentType::class)],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB
        ];
    }
}