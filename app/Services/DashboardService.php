<?php

namespace App\Services;

use App\Models\EstatusOrden;
use App\Models\EstatusOrdenCompra;
use App\Models\EstatusVenta;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Agregaciones del dashboard. Cada método devuelve exactamente el shape
 * que consumen las gráficas del frontend (Apex/listas).
 */
class DashboardService
{
    /** Serie Apex [timestamp_ms, total] de ventas pagadas por día. */
    public function ventasPagadasPorDia(): Collection
    {
        return DB::table('tw_ventas')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('COUNT(id_venta) as total'))
            ->where('id_estatus_venta', EstatusVenta::PAGADA)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get()
            ->map(fn ($fila) => [Carbon::parse($fila->fecha)->timestamp * 1000, (int) $fila->total]);
    }

    public function totalVentasHoy(): int
    {
        return DB::table('tw_ventas')
            ->where('id_estatus_venta', EstatusVenta::PAGADA)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public function acumuladoVentasHoy(): float
    {
        return (float) DB::table('tw_ventas')
            ->where('b_activo', 1)
            ->where('id_estatus_venta', EstatusVenta::PAGADA)
            ->whereDate('created_at', now()->toDateString())
            ->sum('n_total');
    }

    public function ordenesEnRepartoHoy(): int
    {
        return DB::table('tw_ordenes')
            ->where('id_estatus_orden', EstatusOrden::EN_REPARTO)
            ->where('b_activo', 1)
            ->whereDate('d_fecha_asignacion', now()->toDateString())
            ->count();
    }

    public function ordenesCompraHoy(): int
    {
        return DB::table('tw_ordenes_compras')
            ->where('id_estatus_orden_compra', EstatusOrdenCompra::APROBADA)
            ->where('b_activo', 1)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public function requisicionesAprobadasHoy(): int
    {
        return DB::table('tw_requisiciones')
            ->where('id_estatus_requisicion', 3)
            ->where('b_activo', 1)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public function top5ClientesConMasVentas(): Collection
    {
        return DB::table('tw_ventas AS v')
            ->join('tw_clientes AS c', 'c.id_cliente', '=', 'v.id_cliente')
            ->where('v.b_activo', 1)
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->select(
                'c.id_cliente',
                'c.s_nombre_cliente',
                DB::raw('COUNT(v.id_venta) AS total_ventas'),
                DB::raw('SUM(v.n_total) AS monto_total'),
            )
            ->groupBy('c.id_cliente', 'c.s_nombre_cliente')
            ->orderByDesc('monto_total')
            ->limit(5)
            ->get();
    }

    /** @return array{top_mas: Collection, top_menos: Collection} */
    public function top5RefaccionesVendidas(): array
    {
        $base = fn () => DB::table('tr_ventas_refacciones AS d')
            ->join('tw_refacciones AS r', 'r.id_refaccion', '=', 'd.id_refaccion')
            ->join('tw_ventas AS v', 'v.id_venta', '=', 'd.id_venta')
            ->where('d.b_activo', 1)
            ->where('v.b_activo', 1)
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->select(
                'r.id_refaccion',
                'r.s_nombre_refaccion',
                DB::raw('SUM(d.n_cantidad) AS total_vendida'),
            )
            ->groupBy('r.id_refaccion', 'r.s_nombre_refaccion')
            ->limit(5);

        return [
            'top_mas' => $base()->orderByDesc('total_vendida')->get(),
            'top_menos' => $base()->orderBy('total_vendida')->get(),
        ];
    }

    public function ventasPorMetodoPagoHoy(): Collection
    {
        return DB::table('tw_ventas AS v')
            ->join('tc_metodos_pagos AS m', 'm.id_metodo_pago', '=', 'v.id_metodo_pago')
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->where('v.b_activo', 1)
            ->whereDate('v.created_at', now()->toDateString())
            ->where('m.b_activo', 1)
            ->select(
                'm.id_metodo_pago',
                'm.s_metodo_pago',
                DB::raw('COUNT(v.id_venta) AS total_ventas'),
            )
            ->groupBy('m.id_metodo_pago', 'm.s_metodo_pago')
            ->orderByDesc('total_ventas')
            ->get();
    }

    public function top5RefaccionistasPorIngresos(): Collection
    {
        return DB::table('tw_ventas AS v')
            ->join('users AS u', 'v.id_usuario_crea', '=', 'u.id')
            ->join('tw_empleados AS e', 'u.id_empleado', '=', 'e.id_empleado')
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->where('v.b_activo', 1)
            ->select(
                'u.id AS id_usuario',
                DB::raw("CONCAT(e.s_nombre, ' ', e.s_apellido_paterno, ' ', e.s_apellido_materno) AS refaccionista"),
                DB::raw('COUNT(v.id_venta) AS total_ventas'),
                DB::raw('SUM(v.n_total) AS monto_total_vendido'),
            )
            ->groupBy('u.id', 'e.s_nombre', 'e.s_apellido_paterno', 'e.s_apellido_materno')
            ->orderByDesc('monto_total_vendido')
            ->limit(5)
            ->get();
    }

    public function top5RefaccionesCriticas(): Collection
    {
        return DB::table('tw_refacciones AS r')
            ->leftJoin('tc_categorias_refacciones AS c', 'r.id_categoria_refaccion', '=', 'c.id_categoria_refaccion')
            ->where('r.b_activo', 1)
            ->whereColumn('r.n_stock_actual', '<=', 'r.n_stock_minimo')
            ->select(
                'r.id_refaccion',
                'r.s_nombre_refaccion',
                'c.s_categoria_refaccion',
                'r.n_stock_actual',
                'r.n_stock_minimo',
                'r.n_stock_maximo',
                'r.n_tiempo_reposicion',
            )
            ->orderBy('r.n_stock_actual')
            ->limit(5)
            ->get();
    }

    public function topProveedoresRefaccionesActivas(): Collection
    {
        return DB::table('tw_refacciones AS r')
            ->join('tw_proveedores AS p', 'r.id_proveedor', '=', 'p.id_proveedor')
            ->where('r.b_activo', 1)
            ->where('p.b_activo', 1)
            ->select(
                'r.id_proveedor',
                'p.s_proveedor AS proveedor',
                DB::raw('COUNT(r.id_refaccion) AS total_refacciones'),
            )
            ->groupBy('r.id_proveedor', 'p.s_proveedor')
            ->orderByDesc('total_refacciones')
            ->limit(5)
            ->get();
    }
}
