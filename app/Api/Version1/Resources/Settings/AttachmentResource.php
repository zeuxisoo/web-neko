<?php

namespace App\Api\Version1\Resources\Settings;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;

class AttachmentResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'max_size_kb' => $this->resource['max_size_kb'],
            'allowed_mimes' => $this->resource['allowed_mimes'],
            'max_files' => $this->resource['max_files'],
            'max_per_memo' => $this->resource['max_per_memo'],
        ];
    }
}
