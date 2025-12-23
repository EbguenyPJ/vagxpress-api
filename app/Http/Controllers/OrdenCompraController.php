<?php

namespace App\Http\Controllers;


use App\Models\OrdenCompra;
use App\Models\Precotizacion;
use App\Models\PrecotizacionRefaccion;
use App\Models\PrecotizacionServicio;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    public function generarOrdenesCompra(Request $request)
    {
        try {
            $request->validate([
                //'id_usuario_crea' => 'required|integer|exists:users,id',
                'ordenes' => 'required|array|min:1',
                'ordenes.*.id_requisicion' => 'required|integer|exists:tw_requisiciones,id_requisicion',
                'ordenes.*.refacciones' => 'required|array|min:1',
                'ordenes.*.refacciones.*.id_requisicion_refaccion' => 'required|integer|exists:tr_requisiciones_refacciones,id_requisicion_refaccion',
                'ordenes.*.refacciones.*.b_autorizada' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => $e->validator->errors()->first()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {

                $ordenesGeneradas = [];

                // arreglo principal de ordenes (agrupadas por proveedor)
                foreach ($request->ordenes as $ordenData) {

                    $refaccionesAutorizadas = collect($ordenData['refacciones'])->where('b_autorizada', 1);

                    if ($refaccionesAutorizadas->isEmpty()) {
                        continue;
                    }

                    // Crear Orden de Compra
                    $nuevaOrdenCompra = new OrdenCompra();
                    $nuevaOrdenCompra->s_observacion                = 'Generada desde Requisición #' . $ordenData['id_requisicion'];
                    $nuevaOrdenCompra->d_fecha_orden                = Carbon::now();

                    //$nuevaOrdenCompra->d_fecha_recepcion_estimada   = Carbon::now()->addDays(3);

                    $nuevaOrdenCompra->n_total_estimado             = 0;
                    $nuevaOrdenCompra->id_proveedor                 = $ordenData['id_proveedor'] ?? null;
                    $nuevaOrdenCompra->id_requisicion               = $ordenData['id_requisicion'];
                    $nuevaOrdenCompra->id_estatus_orden_compra      = 1; // Creada
                    //$nuevaOrdenCompra->id_usuario_crea              = $request->id_usuario_crea;
                    $nuevaOrdenCompra->save();

                    $nuevaOrdenCompra->s_folio_interno              = 'OC' . $nuevaOrdenCompra->id_orden_compra . '-R' . $ordenData['id_requisicion'] . '-P' .  $ordenData['id_proveedor'];
                    $nuevaOrdenCompra->save();

                    $totalOrden = 0;

                    // Detalle (Refacciones)
                    foreach ($refaccionesAutorizadas as $refaccionData) {

                        $requisicionRefaccion = DB::table('tr_requisiciones_refacciones')
                            ->where('id_requisicion_refaccion', $refaccionData['id_requisicion_refaccion'])
                            ->first();

                        if ($requisicionRefaccion) {

                            DB::table('tr_ordenes_compras_requisiciones_refacciones')->insert([
                                'id_orden_compra'           => $nuevaOrdenCompra->id_orden_compra,
                                'id_requisicion_refaccion'  => $requisicionRefaccion->id_requisicion_refaccion,
                                'n_cantidad_recibida'       => 0,
                                'b_activo'                  => 1,
                                'created_at'                => Carbon::now(),
                                'updated_at'                => Carbon::now()
                            ]);


                            DB::table('tr_requisiciones_refacciones')
                                ->where('id_requisicion_refaccion', $requisicionRefaccion->id_requisicion_refaccion)
                                ->update([
                                    'id_estatus_requisicion' => 3
                                ]);



                            $totalOrden += ($requisicionRefaccion->n_cantidad_solicitada * $requisicionRefaccion->n_costo_unitario);
                        }
                    }

                    $nuevaOrdenCompra->n_total_estimado = $totalOrden;
                    $nuevaOrdenCompra->save();

                    $ordenesGeneradas[] = $nuevaOrdenCompra;
                }

                return response()->json([
                    'status' => 'success',
                    'code' => 201,
                    'message' => 'Órdenes de compra generadas exitosamente.',
                    'data' => $ordenesGeneradas
                ], 201);

            });

        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mostrarOrdenesCompras()
    {
        try {
            $data = DB::table('tw_ordenes_compras AS T1')
                ->leftJoin('tw_proveedores AS T2', 'T2.id_proveedor', '=', 'T1.id_proveedor')
                ->leftJoin('tc_estatus_ordenes_compras AS T3', 'T3.id_estatus_orden_compra', '=', 'T1.id_estatus_orden_compra')
                ->select(
                    'T1.id_orden_compra',
                    'T1.s_folio_interno',
                    'T1.id_requisicion',
                    'T1.id_proveedor',
                    'T2.s_proveedor',
                    'T1.d_fecha_orden',
                    'T1.d_fecha_recepcion_estimada',
                    'T1.n_total_estimado',
                    'T1.id_estatus_orden_compra',
                    'T3.s_estatus_orden_compra',
                )
                ->where('T1.b_activo', 1)
                ->orderBy('T1.id_orden_compra', 'DESC')
                ->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Operación realizada correctamente.',
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mostrarOrdenCompra($id_orden_compra)
    {
        try {
            $data = DB::table('tr_ordenes_compras_requisiciones_refacciones AS T1')
                ->join('tr_requisiciones_refacciones AS T2', 'T1.id_requisicion_refaccion', '=', 'T2.id_requisicion_refaccion')
                ->join('tw_refacciones AS T3', 'T2.id_refaccion', '=', 'T3.id_refaccion')
                ->leftJoin('tc_motivos_pedidos AS T4', 'T2.id_motivo_pedido', '=', 'T4.id_motivo_pedido')
                ->leftJoin('tc_prioridades AS T5', 'T2.id_prioridad', '=', 'T5.id_prioridad')
                ->select(
                    'T3.s_nombre_refaccion',
                    'T3.s_numero_parte',
                    'T2.n_cantidad_sugerida',
                    'T2.n_costo_unitario',
                    'T2.id_motivo_pedido',
                    'T4.s_motivo_pedido',
                    'T2.id_prioridad',
                    'T5.s_prioridad'
                )
                ->where('T1.id_orden_compra', $id_orden_compra)
                ->where('T1.b_activo', 1)
                ->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Operación realizada correctamente.',
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
