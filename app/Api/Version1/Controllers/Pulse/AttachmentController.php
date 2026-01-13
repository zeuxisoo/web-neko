<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Attachment\UploadRequest;
use App\Api\Version1\Resources\Pulse\AttachmentResourceCollection;
use App\Enums\AttachmentKind;
use App\Models\Attachment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            foreach ($attachments as $attachment) {
                Storage::disk('pulse')->delete($storeFolder.'/'.$attachment->filename);
            }
        }

        return new AttachmentResourceCollection($attachments);
    }

    private function processUpload(UploadedFile $file, string $storeFolder): Attachment {
        $mime = $file->getMimeType();

        // generate filename using ulids
        $newFilename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // store to 'pulse' disk
        $file->storeAs($storeFolder, $newFilename, 'pulse');

        return Attachment::create([
            'user_id' => $this->user()->id,
            'kind' => $this->detectKind($mime),
            'filename' => $newFilename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $file->getSize(),
            'sort_order' => 0,
        ]);
    }

    private function detectKind(string $mime): AttachmentKind {
        return match (true) {
            str_starts_with($mime, 'image/') => AttachmentKind::IMAGE,
            str_starts_with($mime, 'video/') => AttachmentKind::VIDEO,
            default => AttachmentKind::FILE,
        };
    }
}
