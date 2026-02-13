<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memo_id',
        'memo_comment_id',
        'content',
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

    /**
     * @return BelongsTo<MemoComment>
     */
    public function parent(): BelongsTo {
        return $this->belongsTo(MemoComment::class, 'memo_comment_id');
    }
}
