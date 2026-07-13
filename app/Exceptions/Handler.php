<?php

namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Convierte toda excepción de rutas API en una respuesta JSON uniforme.
     * El error real siempre queda en el log; al cliente solo llega un
     * mensaje seguro con el código HTTP correcto.
     */
    public function render($request, Throwable $e)
    {
        if (! $request->expectsJson() && ! $request->is('api/*')) {
            return parent::render($request, $e);
        }

        return match (true) {
            $e instanceof ValidationException => ApiResponse::error(
                'Los datos enviados no son válidos',
                422,
                $e->errors()
            ),
            $e instanceof AuthenticationException => ApiResponse::error(
                'No autenticado',
                401
            ),
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => ApiResponse::error(
                'Recurso no encontrado',
                404
            ),
            $e instanceof DomainException => ApiResponse::error(
                $e->getMessage(),
                $e->getStatus()
            ),
            $e instanceof HttpException => ApiResponse::error(
                $e->getMessage() ?: 'Error en la petición',
                $e->getStatusCode()
            ),
            default => $this->renderServerError($e),
        };
    }

    private function renderServerError(Throwable $e)
    {
        report($e);

        return ApiResponse::error(
            config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            500
        );
    }
}
