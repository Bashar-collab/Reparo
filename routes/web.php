<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => __('messages.welcome')];
});

require __DIR__.'/auth.php';
