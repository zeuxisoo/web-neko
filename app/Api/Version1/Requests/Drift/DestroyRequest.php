<?php

namespace App\Api\Version1\Requests\Drift;

use App\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Validation\Rule;

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
                'integer',
                Rule::exists('drifts', 'id')->where('user_id', auth()->id()),
            ],
        ];
    }

    public function prepareForValidation(): void {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
