<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;

class LinkResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
