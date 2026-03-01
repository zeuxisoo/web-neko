<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Bookmark\AddRequest;
use App\Api\Version1\Requests\Pulse\Bookmark\RemoveRequest;
use App\Api\Version1\Resources\Pulse\MemoResourceCollection;
use App\Models\Memo;
use App\Models\MemoBookmark;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkController extends ApiController
{
    public function index(): JsonResource {
        $userId = $this->user()->id;

        $memos = Memo::query()
            ->select('memos.*')
            ->selectRaw('1 as is_bookmarked')
            ->join('memo_bookmarks', function($join) use ($userId) {
                $join->on('memos.id', '=', 'memo_bookmarks.memo_id')
                    ->where('memo_bookmarks.user_id', $userId);
            })
            ->with([
                'user',
                'attachments' => fn(HasMany $attachments) => $attachments->orderBy('sort_order', 'asc'),
                'links',
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ])
            ->latest()
            ->simplePaginate(8);

        return new MemoResourceCollection($memos);
    }

    public function add(AddRequest $request): JsonResponse {
        $input = $request->validated();

        MemoBookmark::create([
            'user_id' => $this->user()->id,
            'memo_id' => $input['memo_id'],
        ]);

        return $this->respondJsonMessage('Bookmark added');
    }

    public function remove(RemoveRequest $request): JsonResponse {
        $input = $request->validated();

        MemoBookmark::where('user_id', $this->user()->id)
            ->where('memo_id', $input['memo_id'])
            ->delete();

        return $this->respondJsonMessage('Bookmark removed');
    }
}
