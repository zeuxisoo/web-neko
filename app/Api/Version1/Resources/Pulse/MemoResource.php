<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResource;
use App\Api\Version1\Resources\Auth\UserResource;
use Illuminate\Http\Request;

class MemoResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'content' => $this->content,
            'tags' => new TagResourceCollection($this->tags),
            'attachments' => new AttachmentResourceCollection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at,
        ];
    }
}
