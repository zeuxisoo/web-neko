<?php

use App\Api\Version1\Controllers\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    // api.auth.*
    Route::prefix('auth')->group(function() {
        Route::post('/login', [Auth\LoginController::class, 'login'])->name('api.auth.login');
    });
});
