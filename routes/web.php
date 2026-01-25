<?php

use Illuminate\Support\Facades\Route;

// Route::fallback(function () {
//     return redirect('/');
// });

// Route::get('/', function() {
//     return view('index');
// });

Route::middleware(['auth:web'])->group(function() {
    Route::prefix('auth')->group(function() {
        Route::get('/verify/{token}', function(string $filename) {
            return "test ok --> {$filename}";
        });
    });
});

Route::get('{path}', function() {
    return view('index');
})->where('path', '(.*)')->name('web.index');
