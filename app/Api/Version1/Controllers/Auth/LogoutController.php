<?php

namespace App\Api\Version1\Controllers\Auth;

use App\Api\Version1\Bases\ApiController;
use App\Models\UserAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends ApiController
{
    public function destroy(Request $request): JsonResponse {
        $user = $request->user();
        $currentToken = $user->currentAccessToken();

        if (method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        } else {
            UserAccessToken::find($currentToken->id)->delete();
        }

        Auth::guard('web')->logout();

        return $this->respondJsonMessage('Successfully logged out');
    }
}
