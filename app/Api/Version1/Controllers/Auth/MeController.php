<?php

namespace App\Api\Version1\Controllers\Auth;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Resources\Auth\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MeController extends ApiController
{
    public function me(): JsonResource {
        $user = $this->user();

        return new UserResource($user);
    }
}
