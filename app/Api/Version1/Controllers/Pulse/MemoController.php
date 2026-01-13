<?php

namespace App\Api\Version1\Controllers\Pulse;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Pulse\Memo\StoreRequest;
use Illuminate\Http\JsonResponse;

class MemoController extends ApiController
{
    public function store(StoreRequest $request): JsonResponse {
        return response()->json($request->all());
    }
}
