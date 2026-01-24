<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Memo\StoreRequest;
use App\Api\Version1\Resources\Pulse\MemoResource;
use App\Enums\TagKind;
use App\Models\Memo;
use App\Models\MemoAttachment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MemoController extends ApiController
{
    public function store(StoreRequest $request): JsonResource {
        $input = $request->validated();

        $memo = DB::transaction(function() use ($input) {
            $userId = $this->user()->id;

            // create memo first
            $memo = Memo::create([
                'user_id' => $userId,
                'content' => $input['content'],
            ]);

            // create tags and prepend default tag `beat` ensure distinct
            array_unshift($input['tags'], 'beat');
            $tags = array_values(array_unique($input['tags']));
            $memo->attachTags($tags, type: TagKind::MEMO->value);

            // update previous uploaded attachment relationship
            $attachmentIds = array_column($input['attachments'], 'id');
            $attachments = MemoAttachment::where('user_id', $userId)
                ->whereIn('id', $attachmentIds)
                ->update([
                    'memo_id' => $memo->id,
                    'user_id' => $this->user()->id,
                ]);

            // load attachment
            $memo->load('attachments');

            return $memo;
        });

        return new MemoResource($memo);
    }
}
