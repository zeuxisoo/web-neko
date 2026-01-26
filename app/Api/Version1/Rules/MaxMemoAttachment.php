<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoAttachment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class MaxMemoAttachment implements ValidationRule
{
    public function __construct(
        protected int $limit = 6
    ) {}

    /**
     * @param  array  $value  This will be the array of attachments
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $totalUploadedAttachment = MemoAttachment::where('user_id', Auth::id())
            ->whereNull('memo_id')
            ->count();

        if ($totalUploadedAttachment >= $this->limit) {
            $fail("You cannot create more than {$this->limit} attachments.");
        }
    }
}
