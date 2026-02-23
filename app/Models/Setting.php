<?php

namespace App\Models;

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

    protected $casts = [
        'value' => \App\Casts\SettingValue::class,
    ];
}
