<?php

namespace App\Services\Settings;

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private const CACHE_KEY = 'app_settings';
    private const CACHE_TTL = 3600;

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->allCached();

        return $settings[$key] ?? $default;
    }

    public function set(string $key, mixed $value, SettingType $type = SettingType::String): void
    {
        $storedValue = $type === SettingType::Json ? json_encode($value) : (string) $value;

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type]
        );

        Cache::forget(self::CACHE_KEY);
    }

    private function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::all()->mapWithKeys(fn (Setting $setting) => [
                $setting->key => $setting->castValue(),
            ])->all();
        });
    }
}