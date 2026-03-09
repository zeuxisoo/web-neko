<?php

namespace App\Api\Version1\Requests\Drift;

use App\Api\Version1\Bases\ApiFormRequest;

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
            'tags' => [
                'array',
                'max:10',
            ],
            'tags.*' => [
                'string',
                'distinct',
                'max:50',
            ],
            'content' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }
}
