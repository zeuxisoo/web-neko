<?php

namespace App\Services;

use App\Casts\SettingValue;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private const CACHE_KEY = 'site_settings';

    /**
     * Get a setting value by key
     */
    public function get(string $key, mixed $default = null): mixed {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public function set(string $key, mixed $value): void {
        Setting::updateOrCreate(['key' => $key], [
            'key' => $key,
            'value' => $value,
            'type' => gettype($value),
        ]);

        $this->clearCache();
    }

    /**
     * Set multiple setting values at once
     *
     * @param  $settings  array<string, mixed>
     */
    public function setMultiple(array $settings): void {
        $cast = new SettingValue();

        $records = array_map(function($value, $key) use ($cast) {
            $casted = $cast->set(null, $key, $value, []);

            return [
                'key' => $key,
                'value' => $casted['value'],
                'type' => $casted['type'],
            ];
        }, $settings, array_keys($settings));

        Setting::upsert($records, ['key'], ['value', 'type']);

        $this->clearCache();
    }

    /**
     * Get all settings
     */
    public function all(): array {
        return Cache::rememberForever(self::CACHE_KEY, function() {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear the settings cache
     */
    public function clearCache(): void {
        Cache::forget(self::CACHE_KEY);
    }
}
