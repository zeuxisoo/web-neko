<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Comment\IndexRequest;
use App\Api\Version1\Requests\Pulse\Comment\StoreRequest;
use App\Api\Version1\Resources\Pulse\MemoCommentResource;
use App\Api\Version1\Resources\Pulse\MemoCommentResourceCollection;
use App\Models\MemoComment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentController extends ApiController
{
    public function index(IndexRequest $request): JsonResource {
        $input = $request->validated();

        $comments = MemoComment::where('memo_id', $input['memo_id'])
            ->with('user')
            ->latest()
            ->simplePaginate(8);

        return new MemoCommentResourceCollection($comments);
    }

    public function store(StoreRequest $request): JsonResource {
        $input = $request->validated();

        $userId = $this->user()->id;

        $comment = MemoComment::create([
            'user_id' => $userId,
            'memo_id' => $input['memo_id'],
            'memo_comment_id' => $input['memo_comment_id'] ?? null,
            'content' => $input['content'],
        ]);

        $comment->load('user');

        return new MemoCommentResource($comment);
    }
}
