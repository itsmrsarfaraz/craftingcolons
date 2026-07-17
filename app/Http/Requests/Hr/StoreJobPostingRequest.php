<?php

namespace App\Http\Requests\Hr;

use App\Enums\EmploymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreJobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-jobs');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'location' => ['nullable', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'assessment_required' => ['boolean'],
            'passing_marks' => ['nullable', 'integer', 'min:0', 'max:100', 'required_if:assessment_required,true'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'assessment_required' => $this->boolean('assessment_required'),
        ]);
    }

    public function slug(): string
    {
        return Str::slug($this->input('title')).'-'.Str::random(6);
    }
}