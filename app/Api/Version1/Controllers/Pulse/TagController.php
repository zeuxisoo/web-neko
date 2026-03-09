<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Resources\Pulse\TagResourceCollection;
use App\Enums\TagKind;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagController extends ApiController
{
    public function index(Request $request): JsonResource {
        $tags = Tag::withType(TagKind::MEMO->value)->get();

        return new TagResourceCollection($tags);
    }
}
