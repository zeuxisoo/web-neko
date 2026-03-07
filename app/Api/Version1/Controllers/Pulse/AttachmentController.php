<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Attachment\DestroyRequest;
use App\Api\Version1\Requests\Pulse\Attachment\IndexRequest;
use App\Api\Version1\Requests\Pulse\Attachment\UploadRequest;
use App\Api\Version1\Resources\Pulse\AttachmentResourceCollection;
use App\Enums\AttachmentKind;
use App\Models\MemoAttachment;
use App\Services\SettingsService;
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
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    public function upload(UploadRequest $request): JsonResource {
        $files = $request->file('files');

        $attachments = [];
        $currentYear = now()->format('Y');
        $currentMonth = now()->format('m');
        $storeFolder = $currentYear.'/'.$currentMonth;

        try {
            DB::transaction(function() use ($files, &$attachments, $storeFolder, $currentYear, $currentMonth) {
                foreach ($files as $file) {
                    $uploadedFile = $this->processUpload($file, $storeFolder, $currentYear, $currentMonth);
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

        $year = $attachment->year;
        $month = sprintf('%02d', $attachment->month);
        $this->cleanupAttachment($attachment, storeFolder: $year.'/'.$month);

        $attachment->delete();

        return $this->respondJsonMessage("Attachment deleted: {$attachment->original_name}");
    }

    public function index(IndexRequest $request): JsonResource {
        $perPage = $this->settingsService->get('pagination.per_page_attachment', 2);

        // step 1: get distinct years with cursor-based pagination
        $yearPaginator = MemoAttachment::selectRaw('year, MAX(created_at) as max_created_at')
            ->where('user_id', $this->user()->id)
            ->groupBy('year')
            ->orderByDesc('max_created_at')
            ->simplePaginate($perPage);

        // extract years from paginator
        $paginatedYears = $yearPaginator->pluck('year')->toArray();

        // handle empty results
        if (empty($paginatedYears)) {
            $collection = new AttachmentResourceCollection(collect([]));
            $collection->links([
                'next' => null,
                'prev' => null,
            ]);
            $collection->meta([
                'current_page' => 1,
                'per_page' => $perPage,
                'first_year' => null,
                'last_year' => null,
            ]);

            return $collection;
        }

        // step 2: get ALL attachments for those years (complete groups)
        $minYear = (int) min($paginatedYears);
        $maxYear = (int) max($paginatedYears);

        $attachments = MemoAttachment::where('user_id', $this->user()->id)
            ->whereBetween('year', [$minYear, $maxYear])
            ->orderByDesc('created_at')
            ->get();

        // step 3: Build pagination metadata
        $firstYear = (int) min($paginatedYears);
        $lastYear = (int) max($paginatedYears);

        // build pagination URLs
        $nextUrl = $yearPaginator->nextPageUrl();
        $prevUrl = $yearPaginator->previousPageUrl();

        $collection = new AttachmentResourceCollection($attachments);
        $collection->links([
            'next' => $nextUrl,
            'prev' => $prevUrl,
        ]);
        $collection->meta([
            'current_page' => $yearPaginator->currentPage() ?? 1,
            'per_page' => $perPage,
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
    private function processUpload(UploadedFile $file, string $storeFolder, int $currentYear, int $currentMonth): MemoAttachment {
        $mime = $file->getMimeType();
        $kind = $this->detectKind($mime);

        // generate filename using ulids
        $newFilename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // store to 'pulse' disk
        $file->storeAs($storeFolder, $newFilename, 'pulse');

        $attachment = MemoAttachment::create([
            'user_id' => $this->user()->id,
            'year' => (int) $currentYear,
            'month' => (int) $currentMonth,
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
