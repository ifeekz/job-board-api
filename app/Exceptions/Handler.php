<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::error('Resource not found', null, 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return ApiResponse::error('Rout does not exist', null, 404);
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage() ?: 'HTTP error';
            return ApiResponse::error($message, null, $statusCode);
        }

        return parent::render($request, $exception);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return ApiResponse::error(
            'Validation failed',
            $exception->errors(),
            $exception->status
        );
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return ApiResponse::error('Unauthenticated.', null, 401);
        }

        return redirect()->guest(route('login'));
    }
}
