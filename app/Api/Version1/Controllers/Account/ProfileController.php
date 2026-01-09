<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Profile\UpdateRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends ApiController
{
    public function update(UpdateRequest $request): JsonResponse {
        $input = $request->validated();

        $user = $request->user();
        $user->update([
            'username' => $input['username'],
            'email' => $input['email'],
        ]);

        $user->currentAccessToken()->delete();

        return $this->respondJsonMessage('Successfully updated profile, Please Login again.');
    }
}
