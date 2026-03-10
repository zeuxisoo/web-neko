<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class Drift extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'user_id',
        'subject',
        'content',
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
