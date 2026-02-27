<?php

use App\Api\Version1\Controllers\Account;
use App\Api\Version1\Controllers\Auth;
use App\Api\Version1\Controllers\Pulse;
use App\Api\Version1\Controllers\Settings;
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
                Route::get('/index', [Pulse\MemoController::class, 'index'])->name('api.pulse.memo.index');
                Route::get('/show/{id}', [Pulse\MemoController::class, 'show'])->name('api.pulse.memo.show');
                Route::post('/update', [Pulse\MemoController::class, 'update'])->name('api.pulse.memo.update');
                Route::get('/destroy/{id}', [Pulse\MemoController::class, 'destroy'])->name('api.pulse.memo.destroy');
            });
            Route::prefix('tag')->group(function() {
                Route::get('/all', [Pulse\TagController::class, 'all'])->name('api.pulse.tag.all');
            });
            Route::prefix('attachment')->group(function() {
                Route::get('/index', [Pulse\AttachmentController::class, 'index'])->name('api.pulse.attachment.index');
                Route::post('/upload', [Pulse\AttachmentController::class, 'upload'])->name('api.pulse.attachment.upload');
                Route::get('/destroy/{id}', [Pulse\AttachmentController::class, 'destroy'])->name('api.pulse.attachment.destroy');
                Route::get('/unsaved', [Pulse\AttachmentController::class, 'unsaved'])->name('api.pulse.attachment.unsaved');
            });
            Route::prefix('link')->group(function() {
                Route::get('/index', [Pulse\LinkController::class, 'index'])->name('api.pulse.link.index');
                Route::post('/store', [Pulse\LinkController::class, 'store'])->name('api.pulse.link.store');
                Route::get('/destroy/{id}', [Pulse\LinkController::class, 'destroy'])->name('api.pulse.link.destroy');
                Route::get('/unsaved', [Pulse\LinkController::class, 'unsaved'])->name('api.pulse.link.unsaved');
                Route::post('/fetch', [Pulse\LinkController::class, 'fetch'])->name('api.pulse.link.fetch');
            });
            Route::prefix('bookmark')->group(function() {
                Route::get('/add/{memo_id}', [Pulse\BookmarkController::class, 'add'])->name('api.pulse.bookmark.add');
                Route::get('/remove/{memo_id}', [Pulse\BookmarkController::class, 'remove'])->name('api.pulse.bookmark.remove');
            });
            Route::prefix('comment')->group(function() {
                Route::get('/index/{memo_id}', [Pulse\CommentController::class, 'index'])->name('api.pulse.comment.index');
                Route::post('/store', [Pulse\CommentController::class, 'store'])->name('api.pulse.comment.store');
            });
        });

        // api.settings.*
        Route::prefix('settings')->group(function() {
            Route::get('/index', [Settings\IndexController::class, 'index'])->name('api.settings.index');
            Route::get('/clear', [Settings\IndexController::class, 'clear'])->name('api.settings.clear');
            Route::prefix('attachment')->group(function() {
                Route::get('/index', [Settings\AttachmentController::class, 'index'])->name('api.settings.attachment.index');
                Route::post('/update', [Settings\AttachmentController::class, 'update'])->name('api.settings.attachment.update');
            });
        });
    });
});
