<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;

class MemoResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'tags' => new MemoTagResourceCollection($this->tags),
            'attachments' => new AttachmentResourceCollection($this->whenLoaded('attachments')),
        ];
    }
}
