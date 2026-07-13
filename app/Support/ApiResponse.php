<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * Respuestas JSON uniformes para todo el API.
 *
 * Éxito:  { status: "success", message, data }
 * Error:  { status: "error", message [, errors] }
 * El código de estado HTTP siempre refleja el resultado real.
 */
class ApiResponse
{
    public static function ok(mixed $data = null, string $message = 'OK'): JsonResponse
    {
        return self::success($data, $message, 200);
    }

    public static function created(mixed $data = null, string $message = 'Recurso creado correctamente'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    public static function success(mixed $data, string $message, int $status): JsonResponse
    {
        $payload = ['status' => 'success', 'message' => $message];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }

    public static function error(string $message, int $status, ?array $errors = null): JsonResponse
    {
        $payload = ['status' => 'error', 'message' => $message];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
