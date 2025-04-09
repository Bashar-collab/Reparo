<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ApiResponseResource;
use Symfony\Component\HttpFoundation\Response;


class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if($token = $request->header('Authorization'))
        {
            try{
        $user = JWTAuth::parseToken()->authenticate();
        // dd()

        // log:info("Role is ", $role);
            // Load the role model from the config file dynamically
        $roleConfig = Config::get('profile_types.' . $role);

        // log:info("Role config is ", $roleConfig);
        if ($user && $user->profilable_type === $roleConfig['model']) {
            return $next($request);
            }
        } catch (Exception $e)
        {
            Log::error('Token parsing failed: ' . $e->getMessage());
            return (new ApiResponseResource(
                [
                    'status' => '401 Unauthorized',
                    'message' => 'Unauthorized access',
                    'data' => null
                ]
            ))->response()->setStatusCode(401);
        }
        } 
            // return response()->json(['error' => 'Unauthorized'], 401);
            return (new ApiResponseResource(
                [
                    'status' => '401 Unauthorized',
                    'message' => 'Unauthorized access',
                    'data' => null
                ]
            ))->response()->setStatusCode(401);
    }
}
