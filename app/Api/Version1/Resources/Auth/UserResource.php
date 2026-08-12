<?php

namespace App\Api\Version1\Resources\Auth;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserResource extends ApiResource
{
    public function toArray(Request $request): array {
        $disk = Storage::disk('avatar');

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'description' => $this->description,
            'avatar' => $this->avatar,
            'link_cover' => $disk->url("cover/{$this->avatar}"),
            'link_thumb' => $disk->url("thumb/{$this->avatar}"),
            'is_admin' => $this->is_admin,
        ];
    }
}
