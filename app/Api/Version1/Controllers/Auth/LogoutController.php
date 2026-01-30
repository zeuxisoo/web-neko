<?php

namespace App\Api\Version1\Controllers\Auth;

use App\Api\Version1\Bases\ApiController;
use App\Models\UserAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\TransientToken;

class LogoutController extends ApiController
{
    public function destroy(Request $request): JsonResponse {
        $user = $request->user();
        $currentToken = $user->currentAccessToken();

        if (method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        } elseif (!($currentToken instanceof TransientToken)) {
            UserAccessToken::find($currentToken->id)->delete();
        } else {
            UserAccessToken::findToken(request()->bearerToken())->delete();
        }

        Auth::guard('web')->logout();

        return $this->respondJsonMessage('Successfully logged out');
    }
}
