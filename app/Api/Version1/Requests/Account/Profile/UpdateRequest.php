<?php

namespace App\Api\Version1\Requests\Account\Profile;

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
        $userId = $this->user()->id;

        return [
            'username' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_]*$/',
                'max:20',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
        ];
    }
}
