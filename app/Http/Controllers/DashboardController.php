<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function ventasPagadasPorDia(): JsonResponse
    {
        $datos = DB::table('tw_ventas')
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(id_venta) as total')
            )
            ->where('id_estatus_venta', 1) // PAGADA
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha', 'asc')
            ->get();

        // Convertimos a formato Apex: [timestamp, total]
        $formatoApex = $datos->map(function ($item) {
            return [
                Carbon::parse($item->fecha)->timestamp * 1000,
                (int) $item->total
            ];
        });

        return response()->json($formatoApex);
    }


    public function ventasHoy(): JsonResponse
    {
        $totalVentasHoy = DB::table('tw_ventas')
            ->where('id_estatus_venta', 1) // PAGADA
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return response()->json([
            'total_ventas_hoy' => $totalVentasHoy
        ]);
    }



    public function ordenesEnRepartoHoy(): JsonResponse
    {
        $totalOrdenesRepartoHoy = DB::table('tw_ordenes')
            ->where('id_estatus_orden', 2) // EN REPARTO
            ->where('b_activo', 1)
            ->whereDate('d_fecha_asignacion', now()->toDateString()) // 👈 FECHA CORRECTA
            ->count();

        return response()->json([
            'total_ordenes_reparto_hoy' => $totalOrdenesRepartoHoy
        ]);
    }


    public function ordenesCompraHoy(): JsonResponse
    {
        $totalOrdenesCompraHoy = DB::table('tw_ordenes_compras')
            ->where('id_estatus_orden_compra', 2) // CREADA
            ->where('b_activo', 1)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return response()->json([
            'total_ordenes_compra_hoy' => $totalOrdenesCompraHoy
        ]);
    }


    public function requisicionesAprobadasHoy(): JsonResponse
    {
        $totalRequisicionesHoy = DB::table('tw_requisiciones')
            ->where('id_estatus_requisicion', 3) // APROBADA
            ->where('b_activo', 1)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return response()->json([
            'total_requisiciones_aprobadas_hoy' => $totalRequisicionesHoy
        ]);
    }




    public function top5ClientesConMasVentas(): JsonResponse
    {
        $data = DB::table('tw_ventas AS T1')
            ->join('tw_clientes AS T2', 'T2.id_cliente', '=', 'T1.id_cliente')
            ->where('T1.b_activo', 1)
            ->where('T1.id_estatus_venta', 1) // solo ventas pagadas
            ->select(
                'T2.id_cliente',
                'T2.s_nombre_cliente',
                DB::raw('COUNT(T1.id_venta) AS total_ventas'),
                DB::raw('SUM(T1.n_total) AS monto_total')
            )
            ->groupBy('T2.id_cliente', 'T2.s_nombre_cliente')
            ->orderByDesc('monto_total') // ordenar por monto total para que el mayor sea 1
            ->limit(5)
            ->get();

        return response()->json($data);
    }


    public function top5RefaccionesVendidas(): JsonResponse
    {
        // Top 5 más vendidas
        $topMas = DB::table('tr_ventas_refacciones AS T1')
            ->join('tw_refacciones AS T2', 'T2.id_refaccion', '=', 'T1.id_refaccion')
            ->join('tw_ventas AS T3', 'T3.id_venta', '=', 'T1.id_venta')
            ->where('T1.b_activo', 1)
            ->where('T3.b_activo', 1)
            ->where('T3.id_estatus_venta', 1) // solo ventas pagadas
            ->select(
                'T2.id_refaccion',
                'T2.s_nombre_refaccion',
                DB::raw('SUM(T1.n_cantidad) AS total_vendida')
            )
            ->groupBy('T2.id_refaccion', 'T2.s_nombre_refaccion')
            ->orderByDesc('total_vendida')
            ->limit(5)
            ->get();

        // Top 5 menos vendidas
        $topMenos = DB::table('tr_ventas_refacciones AS T4')
            ->join('tw_refacciones AS T5', 'T5.id_refaccion', '=', 'T4.id_refaccion')
            ->join('tw_ventas AS T6', 'T6.id_venta', '=', 'T4.id_venta')
            ->where('T4.b_activo', 1)
            ->where('T6.b_activo', 1)
            ->where('T6.id_estatus_venta', 1) // solo ventas pagadas
            ->select(
                'T5.id_refaccion',
                'T5.s_nombre_refaccion',
                DB::raw('SUM(T4.n_cantidad) AS total_vendida')
            )
            ->groupBy('T5.id_refaccion', 'T5.s_nombre_refaccion')
            ->orderBy('total_vendida', 'asc')
            ->limit(5)
            ->get();

        return response()->json([
            'top_mas' => $topMas,
            'top_menos' => $topMenos
        ]);
    }



    public function ventasAcumuladasHoy(): JsonResponse
    {
        $totalVentasHoy = DB::table('tw_ventas AS T1')
            ->where('T1.b_activo', 1)           // solo activas
            ->where('T1.id_estatus_venta', 1)   // solo pagadas
            ->whereDate('T1.created_at', now()->toDateString())
            ->sum('T1.n_total');               // sumamos el total de cada venta

        return response()->json([
            'acumulado_ventas_hoy' => $totalVentasHoy
        ]);
    }


    public function ventasPorMetodoPagoHoy(): JsonResponse
    {
        $ventasPorMetodo = DB::table('tw_ventas AS T1')
            ->join('tc_metodos_pagos AS T2', 'T2.id_metodo_pago', '=', 'T1.id_metodo_pago')
            ->where('T1.id_estatus_venta', 1) // PAGADA
            ->where('T1.b_activo', 1)
            ->whereDate('T1.created_at', now()->toDateString())
            ->where('T2.b_activo', 1)
            ->select(
                'T2.id_metodo_pago',
                'T2.s_metodo_pago',
                DB::raw('COUNT(T1.id_venta) AS total_ventas')
            )
            ->groupBy('T2.id_metodo_pago', 'T2.s_metodo_pago')
            ->orderByDesc('total_ventas')
            ->get();

        return response()->json([
            'ventas_por_metodo_pago_hoy' => $ventasPorMetodo
        ]);
    }



    public function top5RefaccionistasPorIngresos(): JsonResponse
    {
        $refaccionistas = DB::table('tw_ventas AS T1')
            ->join('users AS T2', 'T1.id_usuario_crea', '=', 'T2.id')
            ->join('tw_empleados AS T3', 'T2.id_empleado', '=', 'T3.id_empleado')
            ->where('T1.id_estatus_venta', 1) // PAGADA
            ->where('T1.b_activo', 1)
            ->select(
                'T2.id AS id_usuario',
                DB::raw("
                CONCAT(
                    T3.s_nombre, ' ',
                    T3.s_apellido_paterno, ' ',
                    T3.s_apellido_materno
                ) AS refaccionista
            "),
                DB::raw('COUNT(T1.id_venta) AS total_ventas'),
                DB::raw('SUM(T1.n_total) AS monto_total_vendido')
            )
            ->groupBy(
                'T2.id',
                'T3.s_nombre',
                'T3.s_apellido_paterno',
                'T3.s_apellido_materno'
            )
            ->orderByDesc('monto_total_vendido') // 🔥 AQUÍ ESTÁ EL CAMBIO CLAVE
            ->limit(5)
            ->get();

        return response()->json([
            'top_5_refaccionistas_ingresos' => $refaccionistas
        ]);
    }




    public function top5RefaccionesCriticas(): JsonResponse
    {
        $data = DB::table('tw_refacciones AS T1')
            ->leftJoin(
                'tc_categorias_refacciones AS T2',
                'T1.id_categoria_refaccion',
                '=',
                'T2.id_categoria_refaccion'
            )
            ->where('T1.b_activo', 1)
            ->whereColumn('T1.n_stock_actual', '<=', 'T1.n_stock_minimo')
            ->select(
                'T1.id_refaccion',
                'T1.s_nombre_refaccion',
                'T2.s_categoria_refaccion',
                'T1.n_stock_actual',
                'T1.n_stock_minimo',
                'T1.n_stock_maximo',
                'T1.n_tiempo_reposicion'
            )
            ->orderBy('T1.n_stock_actual', 'ASC')
            ->limit(5)
            ->get();

        return response()->json([
            'top_5_refacciones_criticas' => $data
        ]);
    }



    public function topProveedoresRefaccionesActivas(): JsonResponse
    {
        $data = DB::table('tw_refacciones AS R')
            ->join('tw_proveedores AS P', 'R.id_proveedor', '=', 'P.id_proveedor')
            ->where('R.b_activo', 1)   // Solo refacciones activas
            ->where('P.b_activo', 1)   // Solo proveedores activos
            ->groupBy('R.id_proveedor', 'P.s_proveedor')
            ->select(
                'R.id_proveedor',
                'P.s_proveedor AS proveedor',
                DB::raw('COUNT(R.id_refaccion) AS total_refacciones')
            )
            ->orderByDesc('total_refacciones')
            ->limit(5)
            ->get();

        return response()->json([
            'top_proveedores_refacciones' => $data
        ]);
    }
}
