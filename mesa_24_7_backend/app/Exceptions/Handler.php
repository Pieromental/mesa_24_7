<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use Illuminate\Validation\ValidationException;

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
    
        if ($exception instanceof AuthenticationException) {
        
            return response()->json([
                "code" => 401,
                "status" => false,
                "message" => "Token inválido o expirado"
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            $messages = collect($exception->errors())->flatten()->implode(', ');
            return response()->json([
                "code" => 422,
                "status" => false,
                "title"=>'Error de Validación',
                'message' => $messages,
                'data' => $exception->errors()
            ], 422);
        }
        
        

        return parent::render($request, $exception);
    }
}
