<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AttachmentResource extends ApiResource
{
    public function toArray(Request $request): array {
        $baseUrl = Storage::disk('pulse')->url($this->created_at->format('Y/m'));

        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'original_name' => $this->original_name,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'sort_order' => $this->sort_order,
            'links' => [
                'cover' => $baseUrl.'/'.$this->addFilenameSuffix($this->filename, '_cover'),
                'thumb' => $baseUrl.'/'.$this->addFilenameSuffix($this->filename, '_thumb'),
            ],
        ];
    }

    private function addFilenameSuffix(string $filename, string $suffix): string {
        $extension = File::extension($filename);
        $name = File::name($filename);

        return $name.$suffix.($extension ? '.'.$extension : '');
    }
}
