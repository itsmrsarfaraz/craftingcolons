<?php

namespace App\Http\Requests\Hr;

use App\Enums\EmploymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnboardEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-employees');
    }

    public function rules(): array
    {
        return [
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', Rule::enum(EmploymentType::class)],
            'reports_to' => [
                'nullable',
                Rule::exists('users', 'id')->whereIn('id', function ($query) {
                    $query->select('user_id')->from('role_user')
                        ->whereIn('role_id', function ($q2) {
                            $q2->select('id')->from('roles')->whereIn('slug', ['employee', 'team-lead', 'hr', 'admin']);
                        });
                }),
            ],
            'joined_at' => ['required', 'date'],
        ];
    }
}