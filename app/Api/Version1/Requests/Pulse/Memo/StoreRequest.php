<?php

namespace App\Api\Version1\Requests\Pulse\Memo;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\ValidUserAttachments;

class StoreRequest extends ApiFormRequest
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
            'content' => [
                'required',
                'string',
                'max:5000',
            ],
            'attachments' => [
                'nullable',
                'array',
                'max:8', // limit for attachments same as Attachment\UploadRequest
                new ValidUserAttachments(),
            ],
            // keep check basic structure for the id in children
            'attachments.*.id' => [
                'required',
                'integer',
            ],
        ];
    }
}
