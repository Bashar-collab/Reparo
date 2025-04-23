<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => __('messages.welcome')];
});

Route::get('/logs', function () {
    return response()->file(storage_path('logs/laravel.log'));
});


require __DIR__.'/auth.php';
