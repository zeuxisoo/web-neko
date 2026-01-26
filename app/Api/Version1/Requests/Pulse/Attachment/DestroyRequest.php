<?php

namespace App\Api\Version1\Requests\Pulse\Attachment;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Api\Version1\Rules\ValidAttachmentExists;

class DestroyRequest extends ApiFormRequest
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
            'id' => [
                'required',
                new ValidAttachmentExists(),
            ],
        ];
    }

    public function prepareForValidation(): void {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
