<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
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
