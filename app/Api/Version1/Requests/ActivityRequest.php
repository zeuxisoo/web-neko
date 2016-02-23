<?php
namespace App\Api\Version1\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Api\Version1\Bases\ApiRequest;

class ActivityRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'label_id'    => 'required|exists:activity_labels,id',
            'activity_at' => 'required',
        ];
    }

    public function messages() {
        return [];
    }

}
