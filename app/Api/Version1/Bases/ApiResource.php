<?php

namespace App\Api\Version1\Bases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected bool $ok = true;

    protected string $message = '';

    public function with(Request $request): array {
        return [
            'ok' => $this->ok,
            'message' => $this->message,
        ];
    }
}
