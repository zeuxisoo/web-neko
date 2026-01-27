<?php

namespace App\Api\Version1\Rules;

use App\Models\MemoAttachment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MustMemoAttachmentExists implements ValidationRule
{
    /**
     * @param  array  $value  This will be the array of attachments
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $attachment = MemoAttachment::where('id', $value)->where('user_id', Auth::id())->first();

        if (!$attachment) {
            $fail('The attachment file does not exist or is not owned by you');

            return;
        }

        $storePath = $attachment->created_at->format('Y/m');

        if (!Storage::disk('pulse')->exists($storePath.'/'.$attachment->filename)) {
            $fail('The attachment file [:value] does not exist on the server filesytem')->translate([
                'value' => $attachment->filename,
            ]);
        }
    }
}
