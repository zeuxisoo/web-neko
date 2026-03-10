<?php

namespace App\Api\Version1\Resources\Drift;

use App\Api\Version1\Bases\ApiResource;
use App\Api\Version1\Resources\Auth\UserResource;
use App\Api\Version1\Resources\Pulse\TagResourceCollection;
use Illuminate\Http\Request;

class DriftResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'subject' => $this->subject,
            'content' => $this->content,
            'tags' => new TagResourceCollection($this->tags),
            'created_at' => $this->created_at,
        ];
    }
}
