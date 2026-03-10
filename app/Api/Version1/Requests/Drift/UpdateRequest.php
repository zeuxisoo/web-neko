<?php

namespace App\Api\Version1\Requests\Drift;

use App\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Validation\Rule;

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
            'id' => [
                'required',
                'integer',
                Rule::exists('drifts', 'id')->where('user_id', auth()->id()),
            ],
            'subject' => [
                'required',
                'string',
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
        ];
    }
}
