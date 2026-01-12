<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Attachment\UploadRequest;
use App\Api\Version1\Resources\Pulse\AttachmentResource;
use App\Enums\AttachmentKind;
use App\Models\Attachment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AttachmentController extends ApiController
{
    public function upload(UploadRequest $request): JsonResource {
        $file = $request->file('file');
        $mime = $file->getMimeType();

        // detect kind (image/video/file)
        $kind = $this->detectKind($mime);

        // generate filename (prefix + random suffix + extension)
        $storeFolder = now()->format('Y/m');
        $generateFilename = strtolower((string) Str::ulid()).'_'.Str::random(8);
        $fileExtension = $file->getClientOriginalExtension();
        $newFilename = $generateFilename.'.'.$fileExtension;

        // store to storage folder
        $path = $file->storeAs($storeFolder, $newFilename, 'pulse');

        // store to database
        $attachment = Attachment::create([
            'user_id' => $request->user()->id,
            'kind' => $kind,
            'filename' => $newFilename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $file->getSize(),
            'sort_order' => 0,
        ]);

        // TODO: create resource response !!!!
        return new AttachmentResource($attachment);
    }

    private function detectKind(string $mime): AttachmentKind {
        return match (true) {
            str_starts_with($mime, 'image/') => AttachmentKind::IMAGE,
            str_starts_with($mime, 'video/') => AttachmentKind::VIDEO,
            default => AttachmentKind::FILE,
        };
    }
}
