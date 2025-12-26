<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class RequisicionRefaccionController extends Controller
{
    public function previsualizarPorProveedor($id_requisicion)
    {
        try {
            $refacciones = DB::table('tw_requisiciones AS T1')
                ->leftJoin('tr_requisiciones_refacciones AS T2', 'T2.id_requisicion', '=', 'T1.id_requisicion')
                ->leftJoin('tw_refacciones AS T3', 'T3.id_refaccion', '=', 'T2.id_refaccion')
                ->leftJoin('tw_proveedores AS T4', 'T4.id_proveedor', '=', 'T3.id_proveedor')
                ->leftJoin('tc_prioridades AS T5', 'T5.id_prioridad', '=', 'T2.id_prioridad')
                ->leftJoin('tc_motivos_pedidos AS T6', 'T6.id_motivo_pedido', '=', 'T2.id_motivo_pedido')
                ->select(
                    'T3.id_proveedor',
                    'T4.s_proveedor',

                    // Datos de la Refacción
                    'T3.id_refaccion',
                    'T3.s_nombre_refaccion',
                    'T3.s_numero_parte',
                    'T3.n_stock_actual',
                    DB::raw('(T2.n_cantidad_sugerida * T2.n_costo_unitario) AS costo_estimado_refaccion'),

                    // Datos del Detalle de la Requisición
                    'T2.id_requisicion_refaccion',
                    'T2.n_cantidad_sugerida',
                    'T2.n_costo_unitario',
                    'T2.id_prioridad',
                    'T5.s_prioridad',
                    'T2.id_motivo_pedido',
                    'T6.s_motivo_pedido',
                )
                ->where('T1.id_requisicion', $id_requisicion)
                ->where('T1.b_activo', 1)
                ->where('T2.b_activo', 1)
                ->get();


            $refaccionesProveedor = $refacciones->groupBy('id_proveedor');


            $refaccionesAgrupadas = $refaccionesProveedor->map(function ($refacciones, $id_proveedor) {
                $s_proveedor = $refacciones->first()->s_proveedor ?? 'Proveedor Desconocido';

                $totalProveedor = $refacciones->sum(function($refaccion){
                    $cantidad = $refaccion->n_cantidad_sugerida;
                    return $cantidad * $refaccion->n_costo_unitario;
                });

                $cantidadRefacciones = $refacciones->count();

                return [
                    'id_proveedor' => $id_proveedor,
                    's_proveedor' => $s_proveedor,
                    'total_estimado_proveedor' => $totalProveedor,
                    'cantidad_refacciones_proveedor' => $cantidadRefacciones,
                    'items' => $refacciones->values() // La lista de refacciones de este proveedor
                ];
            })->values();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Operación realizada correctamente.',
                'data' => $refaccionesAgrupadas,
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
