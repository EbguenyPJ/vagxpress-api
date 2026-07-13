<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

/**
 * Indicadores del dashboard. Los shapes de respuesta son los que consumen
 * las gráficas del frontend, sin envoltura adicional.
 */
class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function ventasPagadasPorDia(): JsonResponse
    {
        return response()->json($this->dashboardService->ventasPagadasPorDia());
    }

    public function ventasHoy(): JsonResponse
    {
        return response()->json(['total_ventas_hoy' => $this->dashboardService->totalVentasHoy()]);
    }

    public function acumuladoVentasHoy(): JsonResponse
    {
        return response()->json(['acumulado_ventas_hoy' => $this->dashboardService->acumuladoVentasHoy()]);
    }

    public function ordenesEnRepartoHoy(): JsonResponse
    {
        return response()->json(['total_ordenes_reparto_hoy' => $this->dashboardService->ordenesEnRepartoHoy()]);
    }

    public function ordenesCompraHoy(): JsonResponse
    {
        return response()->json(['total_ordenes_compra_hoy' => $this->dashboardService->ordenesCompraHoy()]);
    }

    public function requisicionesAprobadasHoy(): JsonResponse
    {
        return response()->json(['total_requisiciones_aprobadas_hoy' => $this->dashboardService->requisicionesAprobadasHoy()]);
    }

    public function top5Clientes(): JsonResponse
    {
        return response()->json($this->dashboardService->top5ClientesConMasVentas());
    }

    public function top5RefaccionesVendidas(): JsonResponse
    {
        return response()->json($this->dashboardService->top5RefaccionesVendidas());
    }

    public function ventasPorMetodoPagoHoy(): JsonResponse
    {
        return response()->json(['ventas_por_metodo_pago_hoy' => $this->dashboardService->ventasPorMetodoPagoHoy()]);
    }

    public function top5Refaccionistas(): JsonResponse
    {
        return response()->json(['top_5_refaccionistas_ingresos' => $this->dashboardService->top5RefaccionistasPorIngresos()]);
    }

    public function refaccionesCriticas(): JsonResponse
    {
        return response()->json(['top_5_refacciones_criticas' => $this->dashboardService->top5RefaccionesCriticas()]);
    }

    public function topProveedores(): JsonResponse
    {
        return response()->json(['top_proveedores_refacciones' => $this->dashboardService->topProveedoresRefaccionesActivas()]);
    }
}
