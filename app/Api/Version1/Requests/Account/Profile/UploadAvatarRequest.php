<?php

namespace App\Api\Version1\Requests\Account\Profile;

use App\Api\Version1\Bases\ApiFormRequest;

class UploadAvatarRequest extends ApiFormRequest
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
                'mimes:jpeg,png,jpg,webp',
                'max:8192', // 8MB limit
            ],
        ];
    }
}
