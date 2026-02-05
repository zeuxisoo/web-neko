<?php

namespace App\Api\Version1\Requests\Pulse\Memo;

use App\Api\Version1\Bases\ApiFormRequest;

class IndexRequest extends ApiFormRequest
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
            'tag' => [
                'string',
                'max:50',
            ],
        ];
    }

    public function prepareForValidation(): void {
        $tag = $this->query('tag');

        if ($tag) {
            $this->merge([
                'tag' => $tag,
            ]);
        }
    }
}
