<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memo_id',
        'url',
        'title',
        'description',
        'image',
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Memo>
     */
    public function memo(): BelongsTo {
        return $this->belongsTo(Memo::class);
    }
}
