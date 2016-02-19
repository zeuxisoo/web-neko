<?php
namespace App\Api\Version1\Controllers;

use Illuminate\Http\Request;
use App\Api\Version1\Contracts\ApiController;
use App\Api\Version1\Transformers\UserTransformer;

class UserController extends ApiController {

    public function me(Request $request) {
        return $this->response->item($this->auth->user(), new UserTransformer);
    }

}
