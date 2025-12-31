<?php

namespace App\Api\Version1\Controllers\Auth;

use App\Api\Version1\Bases\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends ApiController
{
    public function destroy(Request $request): JsonResponse {
        Auth::guard('web')->logout();

        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->respondJsonMessage('Successfully logged out');
    }
}
