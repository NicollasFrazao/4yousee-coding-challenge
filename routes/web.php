<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('videos.create');
    //return view('welcome');
});

Route::prefix('videos')
    ->controller(\App\Http\Controllers\VideoController::class)
    ->group(function () {
        Route::get('/create', 'create')->name('videos.create');
    });
