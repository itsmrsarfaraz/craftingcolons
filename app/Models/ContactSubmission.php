<?php

namespace App\Models;

use App\Enums\ContactSubmissionType;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => ContactSubmissionType::class,
            'is_read' => 'boolean',
        ];
    }
}