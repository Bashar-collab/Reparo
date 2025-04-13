<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponseResource;
use Exception;
use Illuminate\Container\Attributes\Auth;

class UserController extends Controller
{
    //
     // Fetch all users
     public function index()
     {
        try {
            $users = User::all();
            return (new ApiResponseResource([
                'status' => '200 Ok',
                'message' => 'Users retrieved successfully', // NEED TO ADD ARABIC TRANSLATION FOR THIS
                'data' => $users
            ]))->response()->setStatusCode(200);
        } catch (Exception $e)
        {
            return (new ApiResponseResource([
                'status' => '400 Bad request',
                'message' => __('messages.failed'),
                'data' => $e
            ]))->response()->setStatusCode(400);
        }

     }
}
