<?php

namespace App\Http\Requests\Assessments;

use App\Enums\ViolationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportViolationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(ViolationType::class)],
            'metadata' => ['nullable', 'array'],
        ];
    }
}