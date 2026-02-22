<?php

namespace App\Services;

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
