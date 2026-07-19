<?php

namespace App\Http\Requests\Staff;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publish-articles') || $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'client_name' => ['nullable', 'string', 'max:150'],
            'project_type' => ['required', Rule::enum(ProjectType::class)],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'project_url' => ['nullable', 'url'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['integer', 'exists:technologies,id'],
            'results' => ['nullable', 'array'],
            'results.*.metric_label' => ['required_with:results', 'string', 'max:100'],
            'results.*.metric_value' => ['required_with:results', 'string', 'max:50'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function slug(): string
    {
        return Str::slug($this->input('title')).'-'.Str::random(6);
    }
}