<?php

namespace App\Http\Requests\Staff;

use App\Enums\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publish-articles') || $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:10'],
            'short_description' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string'],
            'status' => ['required', Rule::enum(ServiceStatus::class)],
            'order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ];
    }

    public function slug(): string
    {
        return Str::slug($this->input('title')).'-'.Str::random(6);
    }
}