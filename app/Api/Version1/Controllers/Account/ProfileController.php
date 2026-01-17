<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Profile\UpdateRequest;
use App\Api\Version1\Requests\Account\Profile\UploadAvatarRequest;
use App\Api\Version1\Resources\Auth\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    public function uploadAvatar(UploadAvatarRequest $request): JsonResource {
        $file = $request->file('file');

        // generate filename and store path
        $filename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $storePath = 'avatar/'.$filename;

        // create thumb image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file)->scaleDown(96, 96);

        // save to store path
        Storage::disk('public')->put($storePath, (string) $image->encode());

        // update avatar
        $user = User::find($this->user()->id);
        $user->avatar = $filename;
        $user->save();

        return new UserResource($user);
    }
}
