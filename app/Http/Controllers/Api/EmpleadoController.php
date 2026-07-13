<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Empleados\ActualizarEmpleadoRequest;
use App\Http\Requests\Empleados\ActualizarHabilidadesRequest;
use App\Http\Requests\Empleados\CrearEmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Services\EmpleadoService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmpleadoController extends Controller
{
    public function __construct(private readonly EmpleadoService $empleadoService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            EmpleadoResource::collection($this->empleadoService->listar()),
            'Empleados obtenidos correctamente'
        );
    }

    public function sinUsuario(): JsonResponse
    {
        return ApiResponse::ok(
            EmpleadoResource::collection($this->empleadoService->sinUsuario()),
            'Empleados obtenidos correctamente'
        );
    }

    public function porSucursal(int $idSucursal): JsonResponse
    {
        return ApiResponse::ok(
            EmpleadoResource::collection($this->empleadoService->porSucursal($idSucursal)),
            'Empleados obtenidos correctamente'
        );
    }

    public function porUsuario(int $idUsuario): JsonResponse
    {
        return ApiResponse::ok(
            new EmpleadoResource($this->empleadoService->porUsuario($idUsuario)),
            'Empleado obtenido correctamente'
        );
    }

    public function gerenteDeSucursal(int $idSucursal): JsonResponse
    {
        return ApiResponse::ok(
            new EmpleadoResource($this->empleadoService->gerenteDeSucursal($idSucursal)),
            'Gerente obtenido correctamente'
        );
    }

    public function store(CrearEmpleadoRequest $request): JsonResponse
    {
        $empleado = $this->empleadoService->crear($request->validated());

        return ApiResponse::created(new EmpleadoResource($empleado), 'Empleado creado con éxito');
    }

    public function update(ActualizarEmpleadoRequest $request, int $idEmpleado): JsonResponse
    {
        $empleado = $this->empleadoService->actualizar($idEmpleado, $request->validated());

        return ApiResponse::ok(new EmpleadoResource($empleado), 'Empleado actualizado con éxito');
    }

    public function habilidades(int $idEmpleado): JsonResponse
    {
        return ApiResponse::ok(
            $this->empleadoService->habilidades($idEmpleado),
            'Habilidades obtenidas correctamente'
        );
    }

    public function actualizarHabilidades(ActualizarHabilidadesRequest $request, int $idEmpleado): JsonResponse
    {
        $habilidades = $this->empleadoService->actualizarHabilidades($idEmpleado, $request->validated('habilidades'));

        return ApiResponse::ok($habilidades, 'Habilidades actualizadas correctamente');
    }
}
