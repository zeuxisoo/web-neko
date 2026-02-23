<?php

namespace App\Api\Version1\Requests\Settings\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;

class UpdateRequest extends ApiFormRequest
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
            'max_size_kb' => ['required', 'integer', 'min:1'],
            'allowed_mimes' => ['required', 'array'],
            'allowed_mimes.*' => ['required', 'string'],
            'max_files' => ['required', 'integer', 'min:1'],
            'max_per_memo' => ['required', 'integer', 'min:1'],
        ];
    }
}
