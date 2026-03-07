<?php

namespace App\Models;

use App\Enums\AttachmentKind;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class MemoAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memo_id',
        'kind',
        'year',
        'month',
        'filename',
        'original_name',
        'mime_type',
        'size',
        'sort_order',
    ];

    protected function casts(): array {
        return [
            'kind' => AttachmentKind::class,
            'year' => 'integer',
            'month' => 'integer',
            'size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    // $attachment->url;
    public function getUrlAttribute(): string {
        $storeFolder = $this->year.'/'.sprintf('%02d', $this->month);
        $storePath = $storeFolder.'/'.$this->filename;

        return Storage::disk('public')->url($storePath);
    }

    // $attachment->readable_size;
    public function getReadableSizeAttribute(): string {
        return Number::fileSize($this->size, precision: 2);
    }
}
