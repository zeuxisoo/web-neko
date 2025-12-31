<?php

use Illuminate\Support\Facades\Route;

// Route::fallback(function () {
//     return redirect('/');
// });

// Route::get('/', function() {
//     return view('index');
// });

Route::get('{path}', function() {
    return view('index');
})->where('path', '(.*)')->name('web.index');
