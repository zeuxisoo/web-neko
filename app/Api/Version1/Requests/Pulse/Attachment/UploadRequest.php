<?php

namespace App\Api\Version1\Requests\Pulse\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\MaxMemoAttachment;

class UploadRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array {
        return [
            'files' => [
                'required',
                'array',
                'min:1',
                'max:8', // limit for attachments same as Memo\StoreRequest
            ],
            'files.*' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,webp,gif',
                'max:8192', // 8MB limit
                new MaxMemoAttachment(6),
            ],
        ];
    }
}
