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
        $builder = MemoAttachment::where('id', $value);

        if (!$builder->exists()) {
            $fail('The attachment file does not exist in the server record');

            return;
        }

        $builder = $builder->where('user_id', Auth::id());
        if (!$builder->exists()) {
            $fail('The attachment file is not owned by you');

            return;
        }

        $attachment = $builder->first();
        $storePath = $attachment->created_at->format('Y/m');
        $filename = $builder->first()->filename;

        if (!Storage::disk('pulse')->exists($storePath.'/'.$filename)) {
            $fail('The attachment file [:value] does not exist on the server filesytem')->translate([
                'value' => $filename,
            ]);

            return;
        }
    }
}
