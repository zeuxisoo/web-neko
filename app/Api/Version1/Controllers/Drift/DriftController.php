<?php

namespace App\Api\Version1\Controllers\Drift;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Drift\StoreRequest;
use App\Api\Version1\Resources\Drift\DriftResource;
use App\Enums\TagKind;
use App\Models\Drift;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Resources\Json\JsonResource;

class DriftController extends ApiController
{
    public function store(StoreRequest $request): JsonResource {
        $input = $request->validated();

        $drift = Drift::create([
            'user_id' => $this->user()->id,
            'content' => $input['content'],
        ]);

        // create tags ensure distinct and lower
        if (!empty($input['tags'])) {
            $tags = array_map('strtolower', array_values(array_unique($input['tags'])));
            $drift->attachTags($tags, type: TagKind::DRIFT->value);
        }

        $drift->load([
            'user',
            'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
        ]);

        return new DriftResource($drift);
    }
}
