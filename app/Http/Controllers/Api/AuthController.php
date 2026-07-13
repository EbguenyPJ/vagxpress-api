<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\SesionResource;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $sesion = $this->authService->login(
            $request->validated('name'),
            $request->validated('password'),
            web: true,
        );

        return ApiResponse::created(
            new SesionResource($sesion),
            '¡Datos correctos, bienvenido a TallerUp!'
        );
    }

    public function loginMovil(LoginRequest $request): JsonResponse
    {
        $sesion = $this->authService->login(
            $request->validated('name'),
            $request->validated('password'),
            web: false,
        );

        return ApiResponse::created(
            new SesionResource($sesion),
            '¡Datos correctos, bienvenido a TallerUp!'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::ok(null, 'Sesión cerrada correctamente');
    }
}
