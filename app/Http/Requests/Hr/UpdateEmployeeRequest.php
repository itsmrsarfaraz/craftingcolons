<?php

namespace App\Http\Requests\Hr;

use App\Enums\EmployeeStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateEmployeeRequest extends FormRequest
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
            'status' => ['required', Rule::enum(EmployeeStatus::class)],
            'reports_to' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Two checks that plain array rules can't express cleanly:
     * 1. The chosen manager must actually hold a staff-side role.
     * 2. An employee can never be set as their own manager.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $reportsTo = $this->input('reports_to');

            if (! $reportsTo) {
                return;
            }

            $employee = $this->route('employee');

            if ($employee && (int) $reportsTo === (int) $employee->user_id) {
                $validator->errors()->add('reports_to', 'An employee cannot be set as their own manager.');

                return;
            }

            $isValidManager = User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['employee', 'team-lead', 'hr', 'admin']))
                ->where('id', $reportsTo)
                ->exists();

            if (! $isValidManager) {
                $validator->errors()->add('reports_to', 'The selected manager is not a valid staff member.');
            }
        });
    }
}