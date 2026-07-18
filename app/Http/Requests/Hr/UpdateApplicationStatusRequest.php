<?php

namespace App\Http\Requests\Hr;

use App\Enums\JobApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-applications') || $this->user()->can('review-candidates');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(JobApplicationStatus::class)],
        ];
    }
}