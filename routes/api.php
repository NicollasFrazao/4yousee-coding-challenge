<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('videos')
    ->controller(\App\Http\Controllers\VideoController::class)
    ->group(function () {
        Route::post('/', 'store')->name('videos.store');
    });