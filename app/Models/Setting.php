<?php

namespace App\Models;

use App\Enums\SettingType;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    public function castValue(): mixed
    {
        return match ($this->type) {
            SettingType::Integer => (int) $this->value,
            SettingType::Boolean => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            SettingType::Json => json_decode($this->value, true),
            SettingType::String => $this->value,
        };
    }
}