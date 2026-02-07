<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
     * @return HasMany<MemoAttachment>
     */
    public function attachments(): HasMany {
        return $this->hasMany(MemoAttachment::class);
    }

    /**
     * Get all bookmarks for this memo
     *
     * @return HasMany<MemoBookmark>
     */
    public function bookmarks(): HasMany {
        return $this->hasMany(MemoBookmark::class);
    }

    /**
     * Get all users who bookmarked this memo
     *
     * @return HasManyThrough<User>
     */
    public function bookmarkedUsers(): HasManyThrough {
        return $this->hasManyThrough(
            related: User::class,
            through: MemoBookmark::class,
            firstKey: 'memo_id',
            secondKey: 'id',
            localKey: 'id',
            secondLocalKey: 'user_id'
        );
    }
}
