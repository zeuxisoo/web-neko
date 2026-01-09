<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Security\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class SecurityController extends ApiController
{
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse {
        $input = $request->validated();

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->validated('new_password')),
        ]);

        $user->currentAccessToken()->delete();

        return $this->respondJsonMessage('Successfully changed password, Please Login again.');
    }
}
