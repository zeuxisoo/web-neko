<?php

namespace App\Api\Version1\Requests\Pulse\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\MaxMemoAttachment;
use App\Services\SettingsService;

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
        $settings = app(SettingsService::class);

        return [
            'files' => [
                'required',
                'array',
                'min:1',
                'max:'.$settings->get('attachment.max_files', 8),
            ],
            'files.*' => [
                'required',
                'file',
                'mimes:'.implode(',', $settings->get('attachment.allowed_mimes', ['jpeg', 'jpg', 'png', 'webp', 'gif'])),
                'max:'.$settings->get('attachment.max_size_kb', 8192),
                new MaxMemoAttachment($settings->get('attachment.max_per_memo', 6)),
            ],
        ];
    }
}
