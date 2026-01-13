<?php

namespace App\Api\Version1\Rules;

use App\Models\Attachment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidUserAttachments implements ValidationRule
{
    /**
     * @param  array  $value  This will be the array of attachments
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!is_array($value)) {
            return;
        }

        // convert ids: [{id: 1}, {id: 2}] -> [1, 2]
        $ids = collect($value)->pluck('id')->filter()->toArray();

        if (empty($ids)) {
            return;
        }

        // find all exists attachments related to user id
        $validCount = Attachment::where('user_id', auth()->id())
            ->whereIn('id', $ids)
            ->count();

        if ($validCount !== count($ids)) {
            $fail('The selected attachments are invalid or do not belong to you.');
        }
    }
}
