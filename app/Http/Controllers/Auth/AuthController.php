<?php

namespace App\Http\Controllers\Auth;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\RegisterUserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\ApiResponseResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    //
    public function store(RegisterUserRequest $request): JsonResponse
    {

        // dd($request);

        Log::info('REGISTER API HIT');
    
        $data = $request->validated(); // CHECKPOINT
    
        
        try 
        {
            DB::beginTransaction();
            
            $profileTypes = Config::get('profile_types');

            // log::info("Profile types: ", $profileTypes);
            
            $profileTypeConfig = $profileTypes[$data['profilable_type']];
            // log::info("Profile type config ", $profileTypeConfig);
            $modelClass = $profileTypeConfig['model'];
            $profileData = [];

            // Extract only the necessary data for the profile model
            foreach ($data as $key => $value) {
                if (in_array($key, (new $modelClass())->getFillable())) {
                    $profileData[$key] = $value;
                }
            }

            $profile = $modelClass::create($profileData);

        if (!$profile) {
            DB::rollBack();
            return (new ApiResponseResource([
                'status' => '500 Internal Server Error',
                'message' => __('messages.failed'),
                'data' => null
            ]))->response()->setStatusCode(500);
        }

        Log::info('PROFILE CREATION COMPLETED'); 

        Log::info('Profile Created', ['profile_id' => $profile->id]);

        Log::info('Profile type issss:', ['profileClass' => get_class($profile)]);
        DB::enableQueryLog();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'profilable_id' => $profile->id,
            'profilable_type' => get_class($profile),
            'password' => Hash::make($data['password']),
        ]);

        Log::info(DB::getQueryLog()); // See actual query

        DB::commit();

        $user->load('profilable'); // Eager load the profile

        // return response()->json(['user' => $user], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return (new ApiResponseResource([
                'status' => '500 Internal Server Error',
                'message' => __('messages.failed'),
                'data' => null
            ]))->response()->setStatusCode(500);
        }

        Log::info('REGISTER VALIDATION COMPLETED');

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'phone_number' => $request->phone_number, // Store phone number
        //     'profilable_type' => $request->profilable_type, // Store profilable type
        //     'profilable_id' => $request->profilable_id, // Store profilable ID
        //     'fcm_token' => $request->fcm_token, // Store fcm_token
        //     'password' => Hash::make($request->password),
        // ]);

        Log::info('REGISTER API COMPLETED', ['user' => $user]);

        event(new Registered($user));

        // Auth::login($user);

        // try {
        //     $token = JWTAuth::fromUser($user);
        // } catch (JWTException $e) {
        //     // return response()->json(['error' => 'Could not create token'], 500);
        //     return (new ApiResponseResource(
        //         [
        //             'status' => '500 Internal Server Error',
        //             'message' => 'Error Creating token',
        //             'data' => null
        //         ]
        //     ))->response()->setStatusCode(500);
        // }

        return (new ApiResponseResource([
            'status' => '201 Created',
            'message' => __('auth.success_registration'),
            'data' => $user
        ]))->response()->setStatusCode(201);
        
    }

    public function login(LoginRequest $request)
    {

        // dd($request);
        $credentials = $request->only('phone_number', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            // return response()->json([
                // 'success' => false,
                // 'message' => 'Invalid phone number or password'
            // ], 401);

            return (new ApiResponseResource([
                'status' => '401 Unauthorized',
                // 'message' => 'Invalid phone number or password',
                'message' => __('auth.password'),
                'data' => null
            ]))->response()->setStatusCode(401); 

        }

        // return response()->json([
        //     'success' => true,
        //     'token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => JWTAuth::getTTL() * 60,
        // ]);

        return (new ApiResponseResource([
            'status' => '200 Ok',
            'message' => __('auth.success_loggedin'),
            'data' => $token
        ]))->response()->setStatusCode(200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => __('messages.failed')], 500);
        }

        return response()->json(['message' => __('auth.success_loggedout')]);
    }

    public function change(ChangePasswordRequest $request)
    {

        $user = Auth::user();

        // dd(get_class_methods($user));


        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => __('validation.current_password')
            ], 400);
        }

        $user->password = Hash::make($request->new_password);

        // Log::info("Password");
        // $user->
        $user->save();

        return response()->json([
            'message' => __('auth.success_change_password')
        ]);
    }
}
