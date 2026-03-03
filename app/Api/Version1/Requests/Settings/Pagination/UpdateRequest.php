<?php

namespace App\Api\Version1\Requests\Settings\Pagination;

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
            'per_page_attachment' => ['required', 'integer', 'min:1', 'max:100'],
            'per_page_bookmark' => ['required', 'integer', 'min:1', 'max:100'],
            'per_page_comment' => ['required', 'integer', 'min:1', 'max:100'],
            'per_page_link' => ['required', 'integer', 'min:1', 'max:100'],
            'per_page_memo' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
