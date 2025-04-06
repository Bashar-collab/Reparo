<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\ApiResponseResource;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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
                'message' => 'User creation failed',
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
                'message' => 'Registration failed',
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

        Auth::login($user);

        return (new ApiResponseResource([
            'status' => '201 Created',
            'message' => 'User Registered Successfully',
            'data' => $user
        ]))->response()->setStatusCode(201);
        
    }
}
