<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Link\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Link\FetchRequest;
use App\Api\Version1\Requests\Pulse\Link\IndexRequest;
use App\Api\Version1\Requests\Pulse\Link\StoreRequest;
use App\Api\Version1\Resources\Pulse\LinkResource;
use App\Api\Version1\Resources\Pulse\LinkResourceCollection;
use App\Models\MemoLink;
use App\Services\SettingsService;
use Embed\Embed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    public function index(IndexRequest $request): JsonResource {
        $input = $request->validated();

        $builder = MemoLink::where('user_id', $this->user()->id);

        if (!empty($input['keyword'])) {
            $keyword = $input['keyword'];
            $builder->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('url', 'like', "%{$keyword}%");
            });
        }

        $links = $builder->latest()->simplePaginate($this->settingsService->get('pagination.per_page_link', 8));

        return new LinkResourceCollection($links);
    }

    public function store(StoreRequest $request): JsonResource {
        $input = $request->validated();

        $link = MemoLink::create([
            'user_id' => $this->user()->id,
            'memo_id' => $input['memo_id'] ?? null,
            'url' => $input['url'],
            'title' => $input['title'],
            'description' => $input['description'],
            'image' => $input['image'],
        ]);

        return new LinkResource($link);
    }

    public function destroy(DestroyRequest $request): JsonResponse {
        $input = $request->validated();

        $link = MemoLink::where('id', $input['id'])
            ->where('user_id', $this->user()->id)
            ->first();

        $link->delete();

        return $this->respondJsonMessage("Link deleted: {$link->url}");
    }

    public function unsaved(): JsonResource {
        $links = MemoLink::where('user_id', $this->user()->id)
            ->whereNull('memo_id')
            ->get();

        return new LinkResourceCollection($links);
    }

    public function fetch(FetchRequest $request): JsonResponse {
        $input = $request->validated();

        try {
            $embed = new Embed();
            $info = $embed->get($input['url']);

            $data = [
                'title' => $info->title ?? '',
                'description' => $info->description ?? '',
                'url' => $info->url ?? $input['url'],
                'image' => $info->image ?? '',
            ];

            return $this->respondJsonData($data);
        } catch (\Exception $e) {
            return $this->respondJsonMessage('Cannot fetch remote url', 422);
        }
    }
}
