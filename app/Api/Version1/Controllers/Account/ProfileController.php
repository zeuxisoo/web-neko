<?php

namespace App\Api\Version1\Controllers\Account;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Account\Profile\UpdateRequest;
use App\Api\Version1\Requests\Account\Profile\UploadAvatarRequest;
use App\Api\Version1\Resources\Auth\UserResource;
use App\Models\UserAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Laravel\Sanctum\TransientToken;

class ProfileController extends ApiController
{
    public function update(UpdateRequest $request): JsonResponse {
        $input = $request->validated();

        $user = $request->user();
        $user->update([
            'username' => $input['username'],
            'email' => $input['email'],
            'description' => $input['description'],
        ]);

        $currentToken = $user->currentAccessToken();

        if (method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        } elseif (!($currentToken instanceof TransientToken)) {
            UserAccessToken::find($currentToken->id)->delete();
        } else {
            UserAccessToken::findToken(request()->bearerToken())->delete();
        }

        return $this->respondJsonMessage('Successfully updated profile, Please Login again.');
    }

    public function uploadAvatar(UploadAvatarRequest $request): JsonResource {
        $file = $request->file('file');
        $disk = Storage::disk('avatar');

        // generate base filename
        $filename = strtolower((string) Str::ulid()).'_'.Str::random(8).'.'.$file->getClientOriginalExtension();

        $uncookedPath = "uncooked/{$filename}";
        $coverPath = "cover/{$filename}";
        $thumbPath = "thumb/{$filename}";

        $manager = new ImageManager(new Driver());

        try {
            // 1. save original (uncooked) first — original is persisted before any processing
            $disk->put($uncookedPath, (string) $manager->read($file)->encode());

            // 2. generate image variants from the saved original
            $saveVariant = function(string $source, string $dest, callable $process) use ($disk, $manager): void {
                $disk->put($dest, (string) $process($manager->read($source))->encode());
            };

            $saveVariant($disk->path($uncookedPath), $coverPath, fn($img) => $img->scaleDown(96, 96));
            $saveVariant($disk->path($uncookedPath), $thumbPath, fn($img) => $img->scaleDown(512, 512));

            return DB::transaction(function() use ($disk, $filename) {
                $user = $this->user();
                $oldAvatar = $user->avatar;

                $user->avatar = $filename;
                $user->save();

                // remove old avatar versions (handles both old flat and new directory format)
                if ($oldAvatar) {
                    $disk->delete($oldAvatar);
                    $disk->delete("uncooked/{$oldAvatar}");
                    $disk->delete("cover/{$oldAvatar}");
                    $disk->delete("thumb/{$oldAvatar}");
                }

                return new UserResource($user);
            });
        } catch (\Exception $e) {
            // remove uploaded avatar on exception
            $disk->delete($uncookedPath);
            $disk->delete($coverPath);
            $disk->delete($thumbPath);
            throw $e;
        }
    }
}
