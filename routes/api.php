<?php

use App\Api\Version1\Controllers\Account;
use App\Api\Version1\Controllers\Auth;
use App\Api\Version1\Controllers\Pulse;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    // api.auth.*
    Route::prefix('auth')->group(function() {
        Route::post('/login', [Auth\LoginController::class, 'login'])->name('api.auth.login');

        Route::middleware('auth:sanctum')->group(function() {
            Route::get('/me', [Auth\MeController::class, 'me'])->name('api.auth.me');
            Route::get('/logout', [Auth\LogoutController::class, 'destroy'])->name('api.auth.logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function() {
        // api.account.*
        Route::prefix('account')->group(function() {
            Route::prefix('profile')->group(function() {
                Route::post('/upload/avatar', [Account\ProfileController::class, 'uploadAvatar'])->name('api.account.profile.upload_avatar');
                Route::post('/update', [Account\ProfileController::class, 'update'])->name('api.account.profile.update');
            });
            Route::prefix('security')->group(function() {
                Route::post('/update/password', [Account\SecurityController::class, 'updatePassword'])->name('api.account.security.update_password');
            });
        });

        // api.pulse.*
        Route::prefix('pulse')->group(function() {
            Route::prefix('memo')->group(function() {
                Route::post('/store', [Pulse\MemoController::class, 'store'])->name('api.pulse.memo.store');
            });
            Route::prefix('attachment')->group(function() {
                Route::post('/upload', [Pulse\AttachmentController::class, 'upload'])->name('api.pulse.attachment.upload');
            });
        });
    });
});
