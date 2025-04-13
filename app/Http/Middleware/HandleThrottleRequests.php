<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class HandleThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Throttle triggered');

        try {
            return $next($request);
        } catch (ThrottleRequestsException $e) {
            Log::info('Excepetion thrown: ', $e);
            $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;

            return response()->json([
                'status'  => '429 Too Many Requests',
                'message' => __('auth.throttle', ['seconds' => $retryAfter]),
                'data'    => null,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
    }
}
