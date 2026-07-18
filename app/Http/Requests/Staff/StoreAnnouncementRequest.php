<?php

namespace App\Http\Requests\Staff;

use App\Enums\AnnouncementAudience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('publish-articles') || $this->user()->can('manage-announcements');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:5000'],
            'audience' => ['required', Rule::enum(AnnouncementAudience::class)],
            'publish_now' => ['boolean'],
        ];
    }
}