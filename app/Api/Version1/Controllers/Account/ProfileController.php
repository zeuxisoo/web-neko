<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Profile\UpdateRequest;
use App\Api\Version1\Requests\Account\Profile\UploadAvatarRequest;
use App\Api\Version1\Resources\Auth\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
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
        $disk = Storage::disk('avatar');

        // generate filename and store path
        $filename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        // create thumb image object
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file)->scaleDown(96, 96);

        try {
            return DB::transaction(function() use ($disk, $filename, $image) {
                // save to store path
                $disk->put($filename, (string) $image->encode());

                // get user and current avatar
                $user = $this->user();
                $oldAvatar = $user->avatar;

                // update avatar
                $user->avatar = $filename;
                $user->save();

                // remove old avatar (ensure changed before remove)
                $disk->delete($oldAvatar);

                return new UserResource($user);
            });
        } catch (\Exception $e) {
            // remove uploaded avatar on exception
            $disk->delete($filename);
            throw $e;
        }
    }
}
