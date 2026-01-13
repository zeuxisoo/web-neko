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
        $formDataIds = collect($value)->pluck('id')->filter()->toArray();

        if (empty($formDataIds)) {
            return;
        }

        // find all exists attachments related to user id
        $validDbIds = Attachment::where('user_id', auth()->id())
            ->whereIn('id', $formDataIds)
            ->pluck('id')
            ->all();

        // find invalid ids
        $invalidIds = array_diff($formDataIds, $validDbIds);

        if (!empty($invalidIds)) {
            $invalidFiles = collect($value)
                ->whereIn('id', $invalidIds)
                ->pluck('filename')
                ->all();

            $filenameList = implode(', ', $invalidFiles);

            $fail("The following files could not be processed: [{$filenameList}]");
        }
    }
}
