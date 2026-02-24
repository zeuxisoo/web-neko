<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoLink;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class MustMemoLinkExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $link = MemoLink::where('id', $value)->where('user_id', Auth::id())->first();

        if (!$link) {
            $fail('The link does not exist or is not owned by you');
        }
    }
}
