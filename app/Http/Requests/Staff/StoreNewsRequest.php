<?php

namespace App\Http\Requests\Staff;

use App\Enums\NewsStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-announcements') || $this->user()->can('publish-articles');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'categories' => ['nullable', 'array'], 'categories.*' => ['integer', 'exists:categories,id'],
            'status' => ['required', Rule::enum(NewsStatus::class)],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ];
    }

    public function slug(): string
    {
        return Str::slug($this->input('title')).'-'.Str::random(6);
    }
}