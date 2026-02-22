<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Cast value based on type column
     */
    protected function value(): Attribute {
        return Attribute::make(
            get: fn($value) => match ($this->type) {
                'array', 'json' => json_decode($value, true),
                'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                'integer' => intval($value),
                'float' => floatval($value),
                default => $value,
            },
            set: fn($value) => match (true) {
                is_array($value) => ['value' => json_encode($value), 'type' => 'array'],
                is_bool($value) => ['value' => $value ? 'true' : 'false', 'type' => 'boolean'],
                is_int($value) => ['value' => (string) $value, 'type' => 'integer'],
                is_float($value) => ['value' => (string) $value, 'type' => 'float'],
                default => ['value' => (string) $value, 'type' => 'string'],
            },
        );
    }
}
