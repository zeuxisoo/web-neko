<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoLink;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MustUserMemoLinks implements ValidationRule
{
    /**
     * @param  array  $value  This will be the array of links
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
        $validDbIds = MemoLink::where('user_id', auth()->id())
            ->whereIn('id', $formDataIds)
            ->pluck('id');

        // find invalid ids that aren't in the DB results
        $invalidIds = $formDataIds->diff($validDbIds);

        if ($invalidIds->isNotEmpty()) {
            $invalidLinks = $formData->whereIn('id', $invalidIds)
                ->map(fn($link) => '('.$link['id'].') '.($link['url'] ?? 'unknown'))
                ->implode(', ');

            $fail("The following links could not be processed: [{$invalidLinks}]");
        }
    }
}
