<?php

namespace App\Api\Version1\Controllers\Drift;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Drift\DestroyRequest;
use App\Api\Version1\Requests\Drift\IndexRequest;
use App\Api\Version1\Requests\Drift\ShowRequest;
use App\Api\Version1\Requests\Drift\StoreRequest;
use App\Api\Version1\Requests\Drift\UpdateRequest;
use App\Api\Version1\Resources\Drift\DriftResource;
use App\Api\Version1\Resources\Drift\DriftResourceCollection;
use App\Enums\TagKind;
use App\Models\Drift;
use App\Services\SettingsService;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DriftController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    public function store(StoreRequest $request): JsonResource {
        $input = $request->validated();

        $drift = Drift::create([
            'user_id' => $this->user()->id,
            'subject' => $input['subject'],
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

    public function index(IndexRequest $request): JsonResource {
        $drifts = Drift::query()
            ->with([
                'user',
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ])
            ->latest()
            ->simplePaginate($this->settingsService->get('pagination.per_page_drift', 8));

        return new DriftResourceCollection($drifts);
    }

    public function show(ShowRequest $request): JsonResource {
        $input = $request->validated();

        $drift = Drift::where('id', $input['id'])
            ->with([
                'user',
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ])
            ->firstOrFail();

        return new DriftResource($drift);
    }

    public function update(UpdateRequest $request): JsonResource {
        $input = $request->validated();

        $drift = Drift::findOrFail($input['id']);
        $drift->update([
            'subject' => $input['subject'],
            'content' => $input['content'],
        ]);

        // sync tags ensure distinct and lower
        if (!empty($input['tags'])) {
            $tags = array_map('strtolower', array_values(array_unique($input['tags'])));
            $drift->syncTagsWithType($tags, type: TagKind::DRIFT->value);
        } else {
            $drift->syncTagsWithType([], type: TagKind::DRIFT->value);
        }

        $drift->load([
            'user',
            'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
        ]);

        return new DriftResource($drift);
    }

    public function destroy(DestroyRequest $request): JsonResponse {
        $input = $request->validated();

        $drift = Drift::findOrFail($input['id']);
        $drift->delete();

        return $this->respondJsonMessage('Drift deleted');
    }
}
