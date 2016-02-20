<?php
namespace App\Api\Version1\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Api\Version1\Bases\ApiRequest;

class DashboardRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'content' => 'required',
        ];
    }

    public function messages() {
        return [];
    }

}
