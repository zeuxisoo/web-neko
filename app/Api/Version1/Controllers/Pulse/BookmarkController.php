<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Bookmark\AddRequest;
use App\Api\Version1\Requests\Pulse\Bookmark\RemoveRequest;
use App\Models\MemoBookmark;
use Illuminate\Http\JsonResponse;

class BookmarkController extends ApiController
{
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
