<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repartos\AsignarOrdenRequest;
use App\Http\Requests\Repartos\RegistrarRepartoRequest;
use App\Services\RepartoService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class RepartoController extends Controller
{
    public function __construct(private readonly RepartoService $repartoService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->listarRepartos(), 'Repartos obtenidos');
    }

    public function ordenesPendientes(): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->ordenesPendientes(), 'Órdenes pendientes');
    }

    public function ordenesAsignadas(int $idRepartidor): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->ordenesAsignadas($idRepartidor), 'Órdenes asignadas');
    }

    public function repartidores(): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->repartidores(), 'Repartidores obtenidos');
    }

    public function asignar(AsignarOrdenRequest $request): JsonResponse
    {
        $this->repartoService->asignar(
            $request->validated('id_orden'),
            $request->validated('id_repartidor'),
        );

        return ApiResponse::ok(null, 'Orden asignada exitosamente');
    }

    public function detalleOrden(int $idOrden): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->detalleOrden($idOrden), 'Detalle de orden');
    }

    public function detalleReparto(int $idOrden): JsonResponse
    {
        return ApiResponse::ok($this->repartoService->detalleReparto($idOrden), 'Detalle de reparto');
    }

    public function store(RegistrarRepartoRequest $request): JsonResponse
    {
        $this->repartoService->registrarReparto($request->validated());

        return ApiResponse::ok(null, 'Reparto guardado');
    }
}
