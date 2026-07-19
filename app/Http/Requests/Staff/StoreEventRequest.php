<?php

namespace App\Http\Requests\Staff;

use App\Enums\EventStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-events');
    }

    public function rules(): array
    {
        $isUpdate = $this->route('event') !== null;

        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255', 'required_if:is_virtual,0'],
            'is_virtual' => ['boolean'],
            'starts_at' => ['required', 'date', $isUpdate ? 'nullable' : 'after:now'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::enum(EventStatus::class)],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_virtual' => $this->boolean('is_virtual')]);
    }

    public function slug(): string
    {
        return Str::slug($this->input('title')).'-'.Str::random(6);
    }
}