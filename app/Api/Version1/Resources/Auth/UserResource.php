<?php

namespace App\Api\Version1\Resources\Auth;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;

class UserResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
        ];
    }
}
