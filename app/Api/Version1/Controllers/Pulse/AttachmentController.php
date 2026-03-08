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
    protected ImageManager $imageManager;

    public function __construct(
        private readonly SettingsService $settingsService,
    ) {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function upload(UploadRequest $request): JsonResource {
        $files = $request->file('files');

        $attachments = [];

        // determine the storage folder once for the batch
        $currentYear = now()->format('Y');
        $currentMonth = now()->format('m');
        $storeFolder = $currentYear.'/'.$currentMonth;

        try {
            DB::transaction(function() use ($files, &$attachments, $storeFolder) {
                foreach ($files as $file) {
                    // process each file; processUpload now extracts year/month from $storeFolder
                    $uploadedFile = $this->processUpload($file, $storeFolder);
                    $attachments[] = $uploadedFile;
                }
            });
        } catch (\Exception $e) {
            // clean up any stored files on failure before re-throwing the exception
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
        $month = sprintf('%02d', $attachment->month); // ensure two-digit month

        // clean up associated files from storage
        $this->cleanupAttachment($attachment, storeFolder: $year.'/'.$month);

        // delete the attachment record from the database
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

    /**
     * Processes a single uploaded file, stores it, and creates a MemoAttachment record.
     *
     * @param  UploadedFile  $file  The file to process.
     * @param  string  $storeFolder  The base folder (e.g., 'YYYY/MM') where files should be stored.
     * @return MemoAttachment The created MemoAttachment model.
     *
     * @throws FileNotFoundException If a source file is not found during image variant generation.
     */
    private function processUpload(UploadedFile $file, string $storeFolder): MemoAttachment {
        $mime = $file->getMimeType();
        $kind = $this->detectKind($mime);

        // generate a unique filename using ULIDs and a random string
        $newFilename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // store the original file in the 'uncooked' subfolder within the pulse disk
        $file->storeAs($storeFolder, 'uncooked/'.$newFilename, 'pulse');

        // extract year and month from the storeFolder string for database storage
        [$year, $month] = explode('/', $storeFolder);
        $year = (int) $year;
        $month = (int) $month;

        // create a new MemoAttachment record
        $attachment = MemoAttachment::create([
            'user_id' => $this->user()->id,
            'year' => $year,
            'month' => $month,
            'kind' => $kind,
            'filename' => $newFilename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $file->getSize(),
            'sort_order' => 0, // Default sort order
        ]);

        // if the attachment is an image, generate cover and thumbnail variants
        if ($attachment->kind === AttachmentKind::IMAGE) {
            $this->generateCover($attachment, $storeFolder);
            $this->generateThumb($attachment, $storeFolder);
        }

        return $attachment;
    }

    /**
     * Detects the attachment kind based on its MIME type.
     *
     * @param  string  $mime  The MIME type of the file.
     * @return AttachmentKind The detected kind (IMAGE, VIDEO, or FILE).
     */
    private function detectKind(string $mime): AttachmentKind {
        return match (true) {
            str_starts_with($mime, 'image/') => AttachmentKind::IMAGE,
            str_starts_with($mime, 'video/') => AttachmentKind::VIDEO,
            default => AttachmentKind::FILE,
        };
    }

    /**
     * Generates a 'cover' image variant for the given attachment.
     *
     * @param  MemoAttachment  $attachment  The attachment model.
     * @param  string  $storeFolder  The base storage folder.
     */
    private function generateCover(MemoAttachment $attachment, string $storeFolder): void {
        $this->generateImageVariant($attachment, $storeFolder, 'cover', fn($image) => $image->cover(48, 48, 'center'));
    }

    /**
     * Generates a 'thumb' image variant for the given attachment.
     *
     * @param  MemoAttachment  $attachment  The attachment model.
     * @param  string  $storeFolder  The base storage folder.
     */
    private function generateThumb(MemoAttachment $attachment, string $storeFolder): void {
        $this->generateImageVariant($attachment, $storeFolder, 'thumb', fn($image) => $image->scaleDown(512, 512));
    }

    /**
     * Generic method to generate an image variant (cover/thumb).
     *
     * @param  MemoAttachment  $attachment  The attachment model.
     * @param  string  $storeFolder  The base storage folder.
     * @param  string  $ownFolder  The subfolder for this variant (e.g., 'cover', 'thumb').
     * @param  callable  $processCallback  A callback function to apply image manipulation (e.g., cover, scaleDown).
     *
     * @throws FileNotFoundException If the original source file for the image is not found.
     */
    private function generateImageVariant(MemoAttachment $attachment, string $storeFolder, string $ownFolder, callable $processCallback): void {
        // construct the full path to the original file in the 'uncooked' folder
        $sourcePath = $storeFolder.'/uncooked/'.$attachment->filename;
        $fullSourcePath = Storage::disk('pulse')->path($sourcePath);

        if (!file_exists($fullSourcePath)) {
            throw new FileNotFoundException("Cannot found source file for image variant: {$sourcePath}");
        }

        // construct the storage path for the generated variant
        $storePath = $storeFolder.'/'.$ownFolder.'/'.$attachment->filename;

        // use Intervention Image to read, process, and encode the image
        $image = $processCallback($this->imageManager->read($fullSourcePath));

        // store the processed image variant
        Storage::disk('pulse')->put($storePath, (string) $image->encode());
    }

    /**
     * Cleans up all associated files for a given attachment from storage.
     *
     * @param  MemoAttachment  $attachment  The attachment model whose files are to be deleted.
     * @param  string  $storeFolder  The base folder (e.g., 'YYYY/MM') where the attachment files are located.
     */
    private function cleanupAttachment(MemoAttachment $attachment, string $storeFolder): void {
        $storage = Storage::disk('pulse');

        // delete the original file from the 'uncooked' folder
        $storage->delete($storeFolder.'/uncooked/'.$attachment->filename);

        // delete the 'cover' variant if it exists
        $storage->delete($storeFolder.'/cover/'.$attachment->filename);

        // delete the 'thumb' variant if it exists
        $storage->delete($storeFolder.'/thumb/'.$attachment->filename);
    }
}
