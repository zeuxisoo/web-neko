<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Attachment\UploadRequest;
use App\Api\Version1\Resources\Pulse\AttachmentResourceCollection;
use App\Enums\AttachmentKind;
use App\Models\Attachment;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
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

    private function processUpload(UploadedFile $file, string $storeFolder): Attachment {
        $mime = $file->getMimeType();

        // generate filename using ulids
        $newFilename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // store to 'pulse' disk
        $file->storeAs($storeFolder, $newFilename, 'pulse');

        $attachment = Attachment::create([
            'user_id' => $this->user()->id,
            'kind' => $this->detectKind($mime),
            'filename' => $newFilename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $file->getSize(),
            'sort_order' => 0,
        ]);

        // generate cover for images
        if ($attachment->kind === AttachmentKind::IMAGE) {
            $this->generateCover($attachment, $storeFolder);
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

    private function generateCover(Attachment $attachment, string $storeFolder): void {
        $sourcePath = $storeFolder.'/'.$attachment->filename;
        $fullSourcePath = Storage::disk('pulse')->path($sourcePath);

        // skip if source file doesn't exist
        if (!file_exists($fullSourcePath)) {
            throw new FileNotFoundException("Cannot found source file: {$sourcePath}");
        }

        // generate cover filename
        $sourceFilename = pathinfo($attachment->filename, PATHINFO_FILENAME);
        $sourceExtension = pathinfo($attachment->filename, PATHINFO_EXTENSION);
        $coverFilename = $sourceFilename.'_cover.'.$sourceExtension;
        $coverPath = $storeFolder.'/'.$coverFilename;

        // Create cover using Intervention Image
        // - resize with max 300x300 maintaining aspect ratio
        $manager = new ImageManager(new Driver());
        $image = $manager->read($fullSourcePath)->cover(48, 48, 'center');

        Storage::disk('pulse')->put(
            path: $coverPath,
            contents: (string) $image->encode()
        );
    }

    private function cleanupAttachment(Attachment $attachment, string $storeFolder): void {
        // delete original file
        Storage::disk('pulse')->delete($storeFolder.'/'.$attachment->filename);

        // delete cover if exists
        $sourceFilename = pathinfo($attachment->filename, PATHINFO_FILENAME);
        $sourceExtension = pathinfo($attachment->filename, PATHINFO_EXTENSION);
        $coverFilename = $sourceFilename.'_cover.'.$sourceExtension;

        Storage::disk('pulse')->delete($storeFolder.'/'.$coverFilename);
    }
}
