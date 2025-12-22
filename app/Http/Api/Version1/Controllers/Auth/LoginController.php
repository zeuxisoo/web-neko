<?php

namespace App\Http\Api\Version1\Controllers\Auth;

use App\Http\Api\Version1\Bases\ApiController;
use App\Http\Api\Version1\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\NewAccessToken;

class LoginController extends ApiController
{

    public function login(LoginRequest $request): JsonResponse {
        $request->authenticate();

        $accountColumn = $request->getAccountColumnName();
        $accountValue = $request->input('account');

        $user = User::where($accountColumn, $accountValue)->first();
        $token = $user->createToken($request->ip());

        return $this->respondWithToken($token);
    }

    // Helper
    protected function respondWithToken(NewAccessToken $token): JsonResponse {
        $data = [
            'access_token' => $token->plainTextToken,
            'token_type' => 'bearer',
        ];

        $expiration = config('sanctum.expiration');

        $data['expires_in'] = $expiration === null
            ? 0
            : $token->accessToken->created_at->addMinutes($expiration)->timestamp;

        return $this->respondJsonData($data);
    }

}
