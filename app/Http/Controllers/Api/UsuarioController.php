<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\ActualizarAccesosRequest;
use App\Http\Requests\Usuarios\ActualizarEstatusRequest;
use App\Http\Requests\Usuarios\ActualizarTipoUsuarioRequest;
use App\Http\Requests\Usuarios\RegistrarUsuarioRequest;
use App\Http\Resources\PerfilUsuarioResource;
use App\Http\Resources\UsuarioResource;
use App\Services\UsuarioService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    public function __construct(private readonly UsuarioService $usuarioService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            UsuarioResource::collection($this->usuarioService->listar()),
            'Usuarios obtenidos correctamente'
        );
    }

    public function store(RegistrarUsuarioRequest $request): JsonResponse
    {
        $user = $this->usuarioService->registrar($request->validated());

        return ApiResponse::created(new UsuarioResource($user), '¡Usuario registrado exitosamente!');
    }

    public function perfil(int $idUsuario): JsonResponse
    {
        return ApiResponse::ok(
            new PerfilUsuarioResource($this->usuarioService->perfil($idUsuario)),
            'Perfil obtenido correctamente'
        );
    }

    public function actualizarAccesos(ActualizarAccesosRequest $request, int $idUsuario): JsonResponse
    {
        $user = $this->usuarioService->actualizarAccesos($idUsuario, $request->validated());

        return ApiResponse::ok(new UsuarioResource($user), 'Módulos actualizados correctamente');
    }

    public function actualizarEstatus(ActualizarEstatusRequest $request, int $idUsuario): JsonResponse
    {
        $user = $this->usuarioService->actualizarEstatus($idUsuario, $request->validated('b_activo'));

        return ApiResponse::ok(new UsuarioResource($user), 'Estado del usuario actualizado correctamente');
    }

    public function actualizarTipoUsuario(ActualizarTipoUsuarioRequest $request, int $idUsuario): JsonResponse
    {
        $user = $this->usuarioService->actualizarTipoUsuario($idUsuario, $request->validated('id_tipo_usuario'));

        return ApiResponse::ok(new UsuarioResource($user), 'Tipo de usuario actualizado correctamente');
    }
}
