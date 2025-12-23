<?php

namespace App\Http\Controllers;

use App\Models\Requisicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisicionController extends Controller
{
    public function mostrarRequisiciones()
    {
        try {
            $data = DB::table('tw_requisiciones AS T1')
                ->leftJoin('tc_estatus_requisiciones AS T2', 'T2.id_estatus_requisicion', '=', 'T1.id_estatus_requisicion')
                ->leftJoin('tc_tipos_requisiciones AS T3', 'T3.id_tipo_requisicion', '=', 'T1.id_tipo_requisicion')
                ->select(
                    'T1.id_requisicion',
                    'T1.n_cantidad_refacciones',
                    'T1.n_total_estimado',
                    'T1.id_estatus_requisicion',
                    'T2.s_estatus_requisicion',
                    'T1.id_tipo_requisicion',
                    'T3.s_tipo_requisicion',
                )
                ->where('T1.b_activo', 1)
                ->orderBy('T1.id_requisicion', 'DESC')
                ->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Operación realizada correctamente.',
                'data' => $data,
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

    public function mostrarRequisicionByID($id_requisicion)
    {
        try {
            $data = DB::table('tw_requisiciones AS T1')
                ->leftJoin('tr_requisiciones_refacciones AS T2', 'T2.id_requisicion', '=', 'T1.id_requisicion')
                ->leftJoin('tw_refacciones AS T3', 'T3.id_refaccion', '=', 'T2.id_refaccion')
                ->leftJoin('tc_motivos_pedidos AS T4', 'T4.id_motivo_pedido', '=', 'T2.id_motivo_pedido')
                ->leftJoin('tc_prioridades AS T5', 'T5.id_prioridad', '=', 'T2.id_prioridad')
                ->leftJoin('tc_estatus_requisiciones AS T6', 'T6.id_estatus_requisicion', '=', 'T1.id_estatus_requisicion')
                ->select(
                    'T1.id_requisicion',
                    'T2.id_refaccion',
                    'T3.s_nombre_refaccion',
                    'T3.s_numero_parte',
                    'T3.n_precio_compra AS n_costo_unitario',
                    'T3.n_precio_venta',
                    'T3.n_stock_actual',
                    'T3.n_tiempo_reposicion',
                    'T2.n_cantidad_sugerida',
                    //'T2.n_costo_unitario',
                    'T2.id_motivo_pedido',
                    'T4.s_motivo_pedido',
                    'T2.id_prioridad',
                    'T5.s_prioridad',
                    'T1.id_estatus_requisicion',
                    'T6.s_estatus_requisicion',
                )
                ->where('T1.id_requisicion', $id_requisicion)
                ->where('T1.b_activo', 1)
                ->where('T2.b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se encontró una refacción activa para este ID.',
                ], 404);
            }





            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Operación realizada correctamente.',
                'data'    => [$data],
            ], 200);

        } catch (\Exception $e) {
            // Error general
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarRequisicion($id_requisicion, Request $request)
    {
        try {
            $request->validate([
                'id_estatus_requisicion' => 'required|integer|exists:tc_estatus_requisiciones,id_estatus_requisicion',
            ]);

            $requisicion = Requisicion::findOrFail($id_requisicion);

            $requisicion->id_estatus_requisicion = $request->id_estatus_requisicion;
            $requisicion->save();


            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Estatus de Requisición actualizado correctamente',
                'data' => $requisicion
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
