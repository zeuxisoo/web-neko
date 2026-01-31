<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Memo extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Attachment>
     */
    public function attachments(): HasMany {
        return $this->hasMany(MemoAttachment::class);
    }
}
