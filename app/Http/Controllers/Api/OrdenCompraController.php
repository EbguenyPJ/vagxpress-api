<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrdenesCompra\GenerarOrdenesCompraRequest;
use App\Http\Requests\OrdenesCompra\GestionarOrdenCompraRequest;
use App\Services\OrdenCompraService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrdenCompraController extends Controller
{
    public function __construct(private readonly OrdenCompraService $ordenCompraService)
    {
    }

    public function index(): JsonResponse
    {
        $ordenes = $this->ordenCompraService->listar()->map(fn ($o) => [
            'id_orden_compra' => $o->id_orden_compra,
            's_folio_interno' => $o->s_folio_interno,
            'id_requisicion' => $o->id_requisicion,
            'id_proveedor' => $o->id_proveedor,
            's_proveedor' => $o->proveedor?->s_proveedor,
            'd_fecha_orden' => $o->d_fecha_orden?->format('Y-m-d'),
            'd_fecha_recepcion_estimada' => $o->d_fecha_recepcion_estimada?->format('Y-m-d'),
            'n_total_estimado' => $o->n_total_estimado,
            'id_estatus_orden_compra' => $o->id_estatus_orden_compra,
            's_estatus_orden_compra' => $o->estatusOrdenCompra?->s_estatus_orden_compra,
        ]);

        return ApiResponse::ok($ordenes, 'Órdenes de compra obtenidas correctamente');
    }

    public function show(int $idOrdenCompra): JsonResponse
    {
        return ApiResponse::ok(
            $this->ordenCompraService->detalle($idOrdenCompra),
            'Orden de compra obtenida correctamente'
        );
    }

    public function store(GenerarOrdenesCompraRequest $request): JsonResponse
    {
        $ordenes = $this->ordenCompraService->generarDesdeRequisiciones(
            $request->validated('ordenes'),
            $request->user()->id,
        );

        return ApiResponse::created($ordenes, 'Órdenes de compra generadas exitosamente');
    }

    public function gestionar(GestionarOrdenCompraRequest $request, int $idOrdenCompra): JsonResponse
    {
        $this->ordenCompraService->gestionar($idOrdenCompra, $request->validated(), $request->user()->id);

        return ApiResponse::ok(null, 'Orden de compra actualizada correctamente');
    }

    public function pdf(int $idOrdenCompra): JsonResponse
    {
        return ApiResponse::ok(
            $this->ordenCompraService->generarPdf($idOrdenCompra),
            'PDF generado correctamente'
        );
    }
}
