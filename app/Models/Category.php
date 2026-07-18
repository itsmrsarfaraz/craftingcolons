<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => CategoryType::class,
        ];
    }
}