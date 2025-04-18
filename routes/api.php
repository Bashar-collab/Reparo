<?php

use Illuminate\Http\Request;
use League\CommonMark\Util\Xml;
use App\Otp\UserRegistrationOtp;
use Illuminate\Support\Facades\Route;
use SadiqSalau\LaravelOtp\Facades\Otp;
// use App\Http\Controllers\AuthController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\ApiResponseResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\AuthController;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // return $request->user();
// });


Route::post('/register', [AuthController::class, 'store'])
    ->middleware('throttle:5,1');

Route::get('/something', [testController::class, 'test']);

Route::post('/login', [AuthController::class, 'login'])
     ->middleware('throttle:5,1');

    //  use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::post('/throttle-test', function (Request $request) {
    Log::info('Throttle route hit');

    return response()->json(['message' => 'You hit the throttle test route']);
})->middleware('throttle:2,1'); // 2 attempts per minute


// Route::get('/users', [UserController::class, 'index']);

// Route::post('/login', [LoginController::class, 'login'])
//      ->middleware(['throttle:login']);

Route::middleware(['jwt:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'change']);
});


// // routes/api.php
// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');