<?php

namespace App\Api\Version1\Requests\Pulse\Bookmark;

use App\Api\Version1\Bases\ApiFormRequest;
use App\Models\MemoBookmark;
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
            'id' => [
                'required',
                'exists:memos,id',
                Rule::unique(MemoBookmark::class)->where(function($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
        ];
    }

    public function prepareForValidation(): void {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
