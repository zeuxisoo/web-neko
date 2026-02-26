<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Link\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Link\FetchRequest;
use App\Api\Version1\Requests\Pulse\Link\StoreRequest;
use App\Api\Version1\Resources\Pulse\LinkResource;
use App\Api\Version1\Resources\Pulse\LinkResourceCollection;
use App\Models\MemoLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use shweshi\OpenGraph\Exceptions\FetchException;
use shweshi\OpenGraph\OpenGraph;

class LinkController extends ApiController
{
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
            $graph = new OpenGraph();
            $meta = $graph->fetch($input['url']);

            $data = [
                'title' => $meta['title'] ?? '',
                'description' => $meta['description'] ?? '',
                'url' => $meta['url'] ?? $input['url'],
                'image' => $meta['image:secure_url'] ?? $meta['image'] ?? '',
                'extra' => [
                    'site_name' => $meta['site_name'] ?? '',
                    'image_attribute' => [
                        'width' => $meta['image:width'] ?? 0,
                        'height' => $meta['image:height'] ?? 0,
                        'alt' => $meta['image:alt'] ?? '',
                        'type' => $meta['image:type'] ?? 'application/octet-stream',
                    ],
                ],
            ];

            return $this->respondJsonData($data);
        } catch (FetchException) {
            return $this->respondJsonMessage('Cannot fetch remote url', 422);
        }
    }
}
