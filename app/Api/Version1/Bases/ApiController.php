<?php

namespace App\Api\Version1\Bases;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    // @param string|null $guard
    protected function user($guard = null): Authenticatable|User {
        $user = Auth::guard($guard)->user();

        if ($user === null) {
            throw new ModelNotFoundException('Cannot found the authorized user');
        }

        return $user;
    }

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
