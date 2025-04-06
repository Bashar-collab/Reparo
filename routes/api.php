<?php

use Illuminate\Http\Request;
use League\CommonMark\Util\Xml;
use App\Otp\UserRegistrationOtp;
use Illuminate\Support\Facades\Route;
use SadiqSalau\LaravelOtp\Facades\Otp;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Resources\ApiResponseResource;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [RegisteredUserController::class, 'store']);

// // routes/api.php
// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');



// // Register a new user
// Route::post('/register', function(RegisterUserRequest $request)
// {
//     $data = $request->validated();

//     $otp = new UserRegistrationOtp($request->all());

//     $otpStatus = Otp::identifier($request->phone_number)->send(
//         $otp,
//         Notification::route('nexmo', $request->phone_number) // Example using Nexmo
//     );

//     return (new ApiResponseResource([
//         'status' => "200 Ok",
//         'message' => $otpStatus['status'],
//         'data' => null
//     ]));
//     // return response()->json(['status' => __($otpStatus['status'])]);
// });
   

use App\Http\Controllers\FirebaseAuthController;

// Route::post('/verify-otp', [FirebaseAuthController::class, 'verifyOtp']);
