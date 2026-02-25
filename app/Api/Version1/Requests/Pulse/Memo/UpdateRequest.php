<?php

namespace App\Api\Version1\Requests\Pulse\Memo;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\MustUserMemoAttachments;
use App\Api\Version1\Rules\MustUserMemoLinks;
use App\Services\SettingsService;
use Illuminate\Validation\Rule;

class UpdateRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return auth()->check();
    }

    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('memos', 'id')->where('user_id', auth()->id()),
            ],
            'content' => [
                'required',
                'string',
                'max:5000',
            ],
            'tags' => [
                'array',
                'max:10',
            ],
            'tags.*' => [
                'string',
                'distinct',
                'max:50',
            ],
            'attachments' => [
                'nullable',
                'array',
                'max:'.$this->settingsService->get('attachment.max_files', 8),
                new MustUserMemoAttachments(),
            ],
            'attachments.*.id' => ['required', 'integer'],
            'attachments.*.filename' => ['required', 'string'],
            'attachments.*.sort_order' => ['required', 'integer'],
            'links' => [
                'nullable',
                'array',
                new MustUserMemoLinks(),
            ],
            'links.*.id' => ['required', 'integer'],
            'links.*.url' => ['required', 'string'],
        ];
    }
}
