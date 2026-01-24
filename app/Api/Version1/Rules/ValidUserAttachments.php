<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoAttachment;
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

        // convert ids: [{id: 1, etc}, {id: 2, etc}] -> [1, 2]
        $formData = collect($value);
        $formDataIds = $formData->pluck('id')->filter();

        if ($formDataIds->isEmpty()) {
            return;
        }

        // find valid ids collection from DB with related user id
        $validDbIds = MemoAttachment::where('user_id', auth()->id())
            ->whereIn('id', $formDataIds)
            ->pluck('id');

        // find invalid ids that aren not in the DB results
        $invalidIds = $formDataIds->diff($validDbIds);

        if ($invalidIds->isNotEmpty()) {
            $filenameList = $formData->whereIn('id', $invalidIds)
                ->pluck('filename')
                ->implode(', ');

            $fail("The following files could not be processed: [{$filenameList}]");
        }
    }
}
