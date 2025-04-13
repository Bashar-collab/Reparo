<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        Log::error('Caught exception: ' . get_class($exception));
        
        // Handle the ThrottleRequestsException
        if ($exception instanceof TooManyRequestsHttpException) {
            
            $seconds = $exception->getHeaders()['Retry-After'] ?? 60;
            // Return a JSON response for throttle errors
            return response()->json([
                'status' => '429 Too Many Requests',
                'message' => __('auth.throttle', ['seconds' => $seconds]),
                'data' => null
            ], 429);
        }
        
        if ($exception instanceof ValidationException) {
            return response()->json(['errors' => $exception->errors()], 422);
        }
        
        return parent::render($request, $exception);
    }

    public function report(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            Log::error('Validation Exception:', ['errors' => $exception->errors()]);
        }

        parent::report($exception);
    }
}
