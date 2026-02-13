<?php

namespace App\Api\Version1\Requests\Pulse\Comment;

use App\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Validation\Rule;

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
            'memo_id' => [
                'required',
                'integer',
                Rule::exists('memos', 'id'),
            ],
            'memo_comment_id' => [
                'nullable',
                'integer',
            ],
            'content' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }
}
