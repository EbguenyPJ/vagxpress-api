<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requisiciones\ActualizarRequisicionRequest;
use App\Services\RequisicionService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class RequisicionController extends Controller
{
    public function __construct(private readonly RequisicionService $requisicionService)
    {
    }

    public function index(): JsonResponse
    {
        $requisiciones = $this->requisicionService->listar()->map(fn ($r) => [
            'id_requisicion' => $r->id_requisicion,
            'n_cantidad_refacciones' => $r->n_cantidad_refacciones,
            'n_total_estimado' => $r->n_total_estimado,
            'id_estatus_requisicion' => $r->id_estatus_requisicion,
            's_estatus_requisicion' => $r->estatusRequisicion?->s_estatus_requisicion,
            'id_tipo_requisicion' => $r->id_tipo_requisicion,
            's_tipo_requisicion' => $r->tipoRequisicion?->s_tipo_requisicion,
        ]);

        return ApiResponse::ok($requisiciones, 'Requisiciones obtenidas correctamente');
    }

    public function show(int $idRequisicion): JsonResponse
    {
        return ApiResponse::ok(
            $this->requisicionService->detalle($idRequisicion),
            'Requisición obtenida correctamente'
        );
    }

    public function update(ActualizarRequisicionRequest $request, int $idRequisicion): JsonResponse
    {
        $requisicion = $this->requisicionService->actualizarEstatus(
            $idRequisicion,
            $request->validated('id_estatus_requisicion'),
            $request->user()->id,
        );

        return ApiResponse::ok($requisicion, 'Estatus de requisición actualizado correctamente');
    }

    public function porProveedor(int $idRequisicion): JsonResponse
    {
        return ApiResponse::ok(
            $this->requisicionService->previsualizarPorProveedor($idRequisicion),
            'Previsualización por proveedor obtenida correctamente'
        );
    }
}
