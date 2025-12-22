<?php

namespace App\Http\Api\Version1\Bases;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{

    // @param array<mixed> $data
    protected function respondJson(bool $ok, array $data, string $message, int $status = 200): JsonResponse {
        return response()->json([
            'ok' => $ok,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    // @param array<mixed> $data
    protected function respondJsonData(array $data, int $status = 200): JsonResponse {
        return $this->respondJson($status === 200, $data, '', $status);
    }

}
