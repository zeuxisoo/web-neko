<?php

namespace App\Api\Version1\Requests\Pulse\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;

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
            'file' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,webp,mp4,mov,pdf,zip,docx',
                'max:8192', // 8MB limit
            ],
        ];
    }
}
