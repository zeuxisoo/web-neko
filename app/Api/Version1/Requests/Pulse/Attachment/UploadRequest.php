<?php

namespace App\Api\Version1\Requests\Pulse\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\MaxMemoAttachment;
use App\Services\SettingsService;

class UploadRequest extends ApiFormRequest
{
    public function __construct(
        private readonly SettingsService $settings,
    ) {}

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
        $allowedMimes = implode(',', $this->setting('attachment.allowed_mimes', ['jpeg', 'jpg', 'png', 'webp', 'gif']));
        $maxSizeKb = $this->setting('attachment.max_size_kb', 8192);
        $maxPerMemo = $this->setting('attachment.max_per_memo', 6);
        $maxFiles = $this->setting('attachment.max_files', 8);

        return [
            'files' => [
                'required',
                'array',
                'min:1',
                'max:'.$$maxFiles,
            ],
            'files.*' => [
                'required',
                'file',
                'mimes:'.$allowedMimes,
                'max:'.$maxSizeKb,
                new MaxMemoAttachment($maxPerMemo),
            ],
        ];
    }

    /**
     * Get a setting value.
     */
    private function setting(string $key, mixed $default = null): mixed {
        return $this->settings->get($key, $default);
    }
}
