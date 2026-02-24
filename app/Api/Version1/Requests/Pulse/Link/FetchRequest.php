<?php

namespace App\Api\Version1\Requests\Pulse\Link;

use App\Api\Version1\Bases\ApiFormRequest;

class FetchRequest extends ApiFormRequest
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
            'url' => [
                'required',
                'string',
                'max:2048',
                'url',
            ],
        ];
    }
}
