<?php

namespace App\Api\Version1\Bases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResourceCollection extends ResourceCollection
{
    protected bool $ok = true;

    protected string $message = '';

    protected array $links = [];

    protected array $meta = [];

    public function with(Request $request): array {
        return array_merge([
            'ok' => $this->ok,
            'message' => $this->message,
        ], $this->links, $this->meta);
    }

    public function links(array $links): self {
        $this->links = ['links' => $links];

        return $this;
    }

    public function meta(array $meta): self {
        $this->meta = ['meta' => $meta];

        return $this;
    }
}
