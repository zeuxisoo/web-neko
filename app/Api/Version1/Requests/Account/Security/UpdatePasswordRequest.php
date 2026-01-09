<?php

namespace App\Api\Version1\Requests\Account\Security;

use App\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends ApiFormRequest
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
            'old_password' => ['required', 'current_password'],

            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ];
    }
}
