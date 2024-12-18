<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;
use Throwable;
use Log;

class CustomHandler extends ExceptionHandler
{
    protected $dontReport = [
        ValidationException::class,
        AuthenticationException::class,
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                Log::error('Exception occurred:', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => 'The requested resource could not be found'
            ], 404);
        });

        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You must be logged in to perform this action'
            ], 401);
        });

        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'The given data was invalid',
                'errors' => $e->errors()
            ], 422);
        });

        $this->renderable(function (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => 'An error occurred while processing your request'
            ], 500);
        });
    }
}
