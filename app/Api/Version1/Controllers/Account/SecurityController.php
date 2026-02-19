<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Security\UpdatePasswordRequest;
use App\Models\UserAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\TransientToken;

class SecurityController extends ApiController
{
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse {
        $input = $request->validated();

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->validated('new_password')),
        ]);

        $currentToken = $user->currentAccessToken();

        if (method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        } elseif (!($currentToken instanceof TransientToken)) {
            UserAccessToken::find($currentToken->id)->delete();
        } else {
            UserAccessToken::findToken(request()->bearerToken())->delete();
        }

        return $this->respondJsonMessage('Successfully changed password, Please Login again.');
    }
}
