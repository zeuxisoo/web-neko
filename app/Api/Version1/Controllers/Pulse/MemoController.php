<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Memo\IndexRequest;
use App\Api\Version1\Requests\Pulse\Memo\StoreRequest;
use App\Api\Version1\Resources\Pulse\MemoResource;
use App\Api\Version1\Resources\Pulse\MemoResourceCollection;
use App\Enums\TagKind;
use App\Models\Memo;
use App\Models\MemoAttachment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

            // create tags ensure distinct and lower
            $tags = array_map('strtolower', array_values(array_unique($input['tags'])));
            $memo->attachTags($tags, type: TagKind::MEMO->value);

            // update previous uploaded attachment relationship
            $attachmentIds = array_column($input['attachments'], 'id');
            $attachments = MemoAttachment::where('user_id', $userId)
                ->whereIn('id', $attachmentIds)
                ->update([
                    'memo_id' => $memo->id,
                    'user_id' => $this->user()->id,
                ]);

            // update sort_order column
            // set related index, update sort_order to db record, bulk update sort_order column
            $sortedAttachmentsIds = collect($input['attachments'])->pluck('sort_order', key: 'id')->toArray();
            $dbAttachments = MemoAttachment::where('user_id', $userId)->whereIn('id', $attachmentIds)->get();
            foreach ($dbAttachments as $attachment) {
                $attachment->sort_order = $sortedAttachmentsIds[$attachment->id];
            }

            MemoAttachment::where('user_id', $userId)
                ->upsert($dbAttachments->toArray(), ['id'], ['sort_order']);

            // load attachment
            $memo->load(['user', 'attachments']);

            return $memo;
        });

        return new MemoResource($memo);
    }

    public function index(IndexRequest $request): JsonResource {
        $input = $request->validated();
        $userId = $this->user()->id;

        $builder = Memo::query()
            ->with([
                'user',
                'attachments' => fn(HasMany $attachments) => $attachments->orderBy('sort_order', 'asc'),
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ])
            ->withExists([
                'bookmarks as is_bookmarked' => fn(Builder $builder) => $builder->where('user_id', $userId),
            ]);

        if (empty($input['tag'])) {
            $builder = $builder->withAnyTagsOfType(TagKind::MEMO->value);
        } else {
            $builder = $builder->withAnyTags($input['tag'], TagKind::MEMO->value);
        }

        $memos = $builder->latest()
            ->simplePaginate(8);

        return new MemoResourceCollection($memos);
    }
}
