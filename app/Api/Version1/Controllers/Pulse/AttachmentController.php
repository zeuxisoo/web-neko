<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Attachment\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Attachment\IndexRequest;
use App\Api\Version1\Requests\Pulse\Attachment\UploadRequest;
use App\Api\Version1\Resources\Pulse\AttachmentResourceCollection;
use App\Enums\AttachmentKind;
use App\Models\MemoAttachment;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class AttachmentController extends ApiController
{
    public function upload(UploadRequest $request): JsonResource {
        $files = $request->file('files');

        $attachments = [];
        $storeFolder = now()->format('Y/m');

        try {
            DB::transaction(function() use ($files, &$attachments, $storeFolder) {
                foreach ($files as $file) {
                    $uploadedFile = $this->processUpload($file, $storeFolder);
                    $attachments[] = $uploadedFile;
                }
            });
        } catch (\Exception $e) {
            // clean up any stored files on failure
            foreach ($attachments as $attachment) {
                $this->cleanupAttachment($attachment, $storeFolder);
            }
            throw $e;
        }

        return new AttachmentResourceCollection($attachments);
    }

    public function destroy(DestroyRequest $request): JsonResponse {
        $input = $request->validated();

        $attachment = MemoAttachment::where('id', $input['id'])
            ->where('user_id', $this->user()->id)
            ->first();

        $this->cleanupAttachment($attachment, $attachment->created_at->format('Y/m'));
        $attachment->delete();

        return $this->respondJsonMessage("Attachment deleted: {$attachment->original_name}");
    }

    public function index(IndexRequest $request): JsonResource {
        $page = (int) $request->query('page', 1);
        $cursor = $request->query('cursor'); // year cursor
        $perPage = 2;

        // get distinct years using cursor-based pagination
        $yearBuilder = MemoAttachment::selectRaw('strftime("%Y", created_at) as year')
            ->where('user_id', $this->user()->id)
            ->groupByRaw('strftime("%Y", created_at)')
            ->orderByDesc('year');

        // apply cursor filter if provided
        if ($cursor) {
            $yearBuilder->where('year', '<', $cursor);
        }

        $years = $yearBuilder->pluck('year')
            ->take($perPage)
            ->toArray();

        // if no years found return empty with pagination info
        if (empty($years)) {
            $collection = new AttachmentResourceCollection(collect([]));
            $collection->links([
                'next' => null,
                'prev' => null,
            ]);
            $collection->meta([
                'current_page' => $page,
                'per_page' => $perPage,
            ]);

            return $collection;
        }

        // get all attachments for those years using BETWEEN (better index usage)
        $minYear = (int) min($years);
        $maxYear = (int) max($years);

        $attachments = MemoAttachment::where('user_id', $this->user()->id)
            ->whereBetween('created_at', [
                "$minYear-01-01 00:00:00",
                "$maxYear-12-31 23:59:59",
            ])
            ->orderByDesc('created_at')
            ->get();

        // build pagination metadata
        $lastYear = (int) max($years);
        $firstYear = (int) min($years);

        // check if there's a next page
        $hasNext = MemoAttachment::selectRaw('strftime("%Y", created_at) as year')
            ->where('user_id', $this->user()->id)
            ->groupByRaw('strftime("%Y", created_at)')
            ->whereRaw('CAST(strftime("%Y", created_at) AS INTEGER) < ?', [$lastYear])
            ->exists();

        // check if there's a previous page
        $hasPrev = $page > 1 || ($cursor && $page > 1);

        $collection = new AttachmentResourceCollection($attachments);

        // build full URLs for pagination links
        $baseUrl = $request->url();

        $nextParams = ['page' => $page + 1, 'cursor' => $firstYear];
        $prevParams = ['page' => $page - 1, 'cursor' => $lastYear];

        $nextUrl = $hasNext ? $baseUrl.'?'.http_build_query($nextParams) : null;
        $prevUrl = $hasPrev ? $baseUrl.'?'.http_build_query($prevParams) : null;

        $collection->links([
            'next' => $nextUrl,
            'prev' => $prevUrl,
        ]);
        $collection->meta([
            'current_page' => $page,
            'per_page' => $perPage,
            'path' => $baseUrl,
            'first_year' => $firstYear,
            'last_year' => $lastYear,
        ]);

        return $collection;
    }

    public function unsaved(): JsonResource {
        $attachments = MemoAttachment::where('user_id', $this->user()->id)
            ->whereNull('memo_id')
            ->get();

        return new AttachmentResourceCollection($attachments);
    }

    // helpers
    private function processUpload(UploadedFile $file, string $storeFolder): MemoAttachment {
        $mime = $file->getMimeType();
        $kind = $this->detectKind($mime);

        // generate filename using ulids
        $newFilename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // store to 'pulse' disk
        $file->storeAs($storeFolder, $newFilename, 'pulse');

        $attachment = MemoAttachment::create([
            'user_id' => $this->user()->id,
            'kind' => $kind,
            'filename' => $newFilename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $file->getSize(),
            'sort_order' => 0,
        ]);

        // generate cover and thumb for images
        if ($attachment->kind === AttachmentKind::IMAGE) {
            $this->generateCover($attachment, $storeFolder);
            $this->generateThumb($attachment, $storeFolder);
        }

        return $attachment;
    }

    private function detectKind(string $mime): AttachmentKind {
        return match (true) {
            str_starts_with($mime, 'image/') => AttachmentKind::IMAGE,
            str_starts_with($mime, 'video/') => AttachmentKind::VIDEO,
            default => AttachmentKind::FILE,
        };
    }

    private function generateCover(MemoAttachment $attachment, string $storeFolder): void {
        $this->generateImageVariant($attachment, $storeFolder, 'cover', fn($image) => $image->cover(48, 48, 'center'));
    }

    private function generateThumb(MemoAttachment $attachment, string $storeFolder): void {
        $this->generateImageVariant($attachment, $storeFolder, 'thumb', fn($image) => $image->scaleDown(512, 512));
    }

    // @param callable $processCallback Call generate action (cover/scaleDown)
    private function generateImageVariant(MemoAttachment $attachment, string $storeFolder, string $ownFolder, callable $processCallback): void {
        $sourcePath = $storeFolder.'/'.$attachment->filename;
        $fullSourcePath = Storage::disk('pulse')->path($sourcePath);

        if (!file_exists($fullSourcePath)) {
            throw new FileNotFoundException("Cannot found source file: {$sourcePath}");
        }

        // store path for generated file (cover/thumb)
        $storePath = $storeFolder.'/'.$ownFolder.'/'.$attachment->filename;

        $manager = new ImageManager(new Driver());
        $image = $processCallback($manager->read($fullSourcePath));

        Storage::disk('pulse')->put($storePath, (string) $image->encode());
    }

    private function cleanupAttachment(MemoAttachment $attachment, string $storeFolder): void {
        // delete original file
        Storage::disk('pulse')->delete($storeFolder.'/'.$attachment->filename);

        // delete cover if exists
        Storage::disk('pulse')->delete($storeFolder.'/cover/'.$attachment->filename);

        // delete thumb if exists
        Storage::disk('pulse')->delete($storeFolder.'/thumb/'.$attachment->filename);
    }
}
