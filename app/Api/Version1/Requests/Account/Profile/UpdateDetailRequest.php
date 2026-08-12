<?php

namespace App\Api\Version1\Requests\Account\Profile;

use App\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Contracts\Validation\Rule;

class UpdateDetailRequest extends ApiFormRequest
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
     * @return array<string, array|Rule|string>
     */
    public function rules(): array {
        $userId = $this->user()->id;

        return [
            'description' => [
                'required',
            ],
        ];
    }
}
