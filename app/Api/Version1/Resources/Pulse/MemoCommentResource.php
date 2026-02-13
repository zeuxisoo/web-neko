<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResource;
use App\Api\Version1\Resources\Auth\UserResource;
use Illuminate\Http\Request;

class MemoCommentResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'memo_id' => $this->memo_id,
            'memo_comment_id' => $this->memo_comment_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
