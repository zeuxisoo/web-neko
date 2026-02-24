<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoLink;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class MustNotUnsavedMemoLinkExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $exists = MemoLink::where('url', $value)
            ->where('user_id', Auth::id())
            ->whereNull('memo_id')
            ->exists();

        if ($exists) {
            $fail('The unsaved link already exists');
        }
    }
}
