<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Bookmark\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Bookmark\StoreRequest;
use App\Models\MemoBookmark;
use Illuminate\Http\JsonResponse;

class BookmarkController extends ApiController
{
    public function store(StoreRequest $request): JsonResponse {
        $input = $request->validated();

        MemoBookmark::create([
            'user_id' => $this->user()->id,
            'memo_id' => $input['id'],
        ]);

        return $this->respondJsonMessage('Memo bookmarked');
    }

    public function destroy(DestroyRequest $request): JsonResponse {
        $input = $request->validated();

        MemoBookmark::where('user_id', $this->user()->id)
            ->where('memo_id', $input['id'])
            ->delete();

        return $this->respondJsonMessage('Memo bookmark deleted');
    }
}
