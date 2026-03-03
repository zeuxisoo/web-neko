<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Memo\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Memo\IndexRequest;
use App\Api\Version1\Requests\Pulse\Memo\ShowRequest;
use App\Api\Version1\Requests\Pulse\Memo\StoreRequest;
use App\Api\Version1\Requests\Pulse\Memo\UpdateRequest;
use App\Api\Version1\Resources\Pulse\MemoResource;
use App\Api\Version1\Resources\Pulse\MemoResourceCollection;
use App\Enums\TagKind;
use App\Models\Memo;
use App\Models\MemoAttachment;
use App\Models\MemoLink;
use App\Services\SettingsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemoController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

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
            if (!empty($input['attachments'])) {
                $attachmentIds = array_column($input['attachments'], 'id');
                $attachments = MemoAttachment::where('user_id', $userId)
                    ->whereIn('id', $attachmentIds)
                    ->update([
                        'memo_id' => $memo->id,
                    ]);

                // update sort_order column in attachment
                // set related index, update sort_order to db record, bulk update sort_order column
                $sortedAttachmentsIds = collect($input['attachments'])->pluck('sort_order', key: 'id')->toArray();
                $dbAttachments = MemoAttachment::where('user_id', $userId)->whereIn('id', $attachmentIds)->get();
                foreach ($dbAttachments as $attachment) {
                    $attachment->sort_order = $sortedAttachmentsIds[$attachment->id];
                }

                MemoAttachment::where('user_id', $userId)
                    ->upsert($dbAttachments->toArray(), ['id'], ['sort_order']);
            }

            // update previous created link relationship
            if (!empty($input['links'])) {
                $linkIds = array_column($input['links'], 'id');
                MemoLink::where('user_id', $userId)
                    ->whereIn('id', $linkIds)
                    ->update([
                        'memo_id' => $memo->id,
                    ]);
            }

            // load attachment
            $memo->load([
                'user',
                'attachments' => fn(HasMany $attachments) => $attachments->orderBy('sort_order', 'asc'),
                'links',
            ]);

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
                'links',
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
            ->simplePaginate($this->settingsService->get('pagination.per_page_memo', 8));

        return new MemoResourceCollection($memos);
    }

    public function show(ShowRequest $request): JsonResource {
        $input = $request->validated();
        $userId = $this->user()->id;

        $memo = Memo::where('id', $input['id'])
            ->with([
                'user',
                'attachments' => fn(HasMany $attachments) => $attachments->orderBy('sort_order', 'asc'),
                'links',
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ])
            ->withExists([
                'bookmarks as is_bookmarked' => fn(Builder $builder) => $builder->where('user_id', $userId),
            ])
            ->firstOrFail();

        return new MemoResource($memo);
    }

    public function update(UpdateRequest $request): JsonResource {
        $input = $request->validated();

        $memo = DB::transaction(function() use ($input) {
            $userId = $this->user()->id;

            // find memo
            $memo = Memo::findOrFail($input['id']);

            // update memo content
            $memo->update([
                'content' => $input['content'],
            ]);

            // sync tags ensure distinct and lower
            $tags = array_map('strtolower', array_values(array_unique($input['tags'])));
            $memo->syncTagsWithType($tags, type: TagKind::MEMO->value);

            // update attachment relationship and sort_order
            if (!empty($input['attachments'])) {
                $attachmentIds = array_column($input['attachments'], 'id');

                // update memo_id for new attachments
                MemoAttachment::where('user_id', $userId)
                    ->whereIn('id', $attachmentIds)
                    ->whereNull('memo_id')
                    ->update([
                        'memo_id' => $memo->id,
                    ]);

                // update sort_order column
                $sortedAttachmentsIds = collect($input['attachments'])->pluck('sort_order', key: 'id')->toArray();
                $dbAttachments = MemoAttachment::where('user_id', $userId)
                    ->whereIn('id', $attachmentIds)
                    ->get();
                foreach ($dbAttachments as $attachment) {
                    $attachment->sort_order = $sortedAttachmentsIds[$attachment->id];
                }

                MemoAttachment::where('user_id', $userId)
                    ->whereIn('id', $attachmentIds)
                    ->upsert($dbAttachments->toArray(), ['id'], ['sort_order']);
            }

            // update link relationship
            if (!empty($input['links'])) {
                $linkIds = array_column($input['links'], 'id');

                // update memo_id for new links
                MemoLink::where('user_id', $userId)
                    ->whereIn('id', $linkIds)
                    ->whereNull('memo_id')
                    ->update([
                        'memo_id' => $memo->id,
                    ]);
            }

            // load relations
            $memo->load([
                'user',
                'attachments' => fn(HasMany $attachments) => $attachments->orderBy('sort_order', 'asc'),
                'links',
                'tags' => fn(MorphToMany $tags) => $tags->orderBy('name', 'asc'),
            ]);

            return $memo;
        });

        return new MemoResource($memo);
    }

    public function destroy(DestroyRequest $request): JsonResponse {
        $input = $request->validated();

        DB::transaction(function() use ($input) {
            $memo = Memo::findOrFail($input['id']);

            // delete attachment files from storage (original, cover, thumb), then database records
            if ($memo->attachments) {
                foreach ($memo->attachments as $attachment) {
                    $storeFolder = $attachment->created_at->format('Y/m');

                    // delete original file
                    Storage::disk('pulse')->delete($storeFolder.'/'.$attachment->filename);

                    // delete cover variant
                    Storage::disk('pulse')->delete($storeFolder.'/cover/'.$attachment->filename);

                    // delete thumb variant
                    Storage::disk('pulse')->delete($storeFolder.'/thumb/'.$attachment->filename);
                }

                $memo->attachments()->delete();
            }

            // detach all tags (removes pivot table records only)
            $memo->tags()->detach();

            // delete bookmark records
            $memo->bookmarks()->delete();

            // delete memo
            $memo->delete();
        });

        return $this->respondJsonMessage('Memo deleted');
    }
}
