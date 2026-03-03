<?php

namespace App\Api\Version1\Resources\Settings;

use App\Api\Version1\Bases\ApiResource;
use Illuminate\Http\Request;

class PaginationResource extends ApiResource
{
    public function toArray(Request $request): array {
        return [
            'per_page_attachment' => $this->resource['per_page_attachment'],
            'per_page_bookmark' => $this->resource['per_page_bookmark'],
            'per_page_comment' => $this->resource['per_page_comment'],
            'per_page_link' => $this->resource['per_page_link'],
            'per_page_memo' => $this->resource['per_page_memo'],
        ];
    }
}
