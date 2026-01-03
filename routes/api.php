<?php

use App\Api\Version1\Controllers\Account;
use App\Api\Version1\Controllers\Auth;
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
                Route::post('/update', [Account\ProfileController::class, 'update'])->name('api.account.profile.update');
            });
        });
    });
});
