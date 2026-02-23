<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

// @implements CastsAttributes<mixed,mixed>
class SettingValue implements CastsAttributes
{
    /**
     * Cast the stored value to the desired type
     */
    public function get($model, string $key, $value, array $attributes): mixed {
        $type = $attributes['type'] ?? 'string';

        return match ($type) {
            'array', 'json' => json_decode($value, true),
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => intval($value),
            'float' => floatval($value),
            default => $value,
        };
    }

    /**
     * Prepare the given value for storage
     *
     * @return array{value: string, type: string}
     */
    public function set($model, string $key, $value, array $attributes): array {
        return match (true) {
            is_array($value) => ['value' => json_encode($value), 'type' => 'array'],
            is_bool($value) => ['value' => $value ? 'true' : 'false', 'type' => 'boolean'],
            is_int($value) => ['value' => (string) $value, 'type' => 'integer'],
            is_float($value) => ['value' => (string) $value, 'type' => 'float'],
            default => ['value' => (string) $value, 'type' => 'string'],
        };
    }
}
