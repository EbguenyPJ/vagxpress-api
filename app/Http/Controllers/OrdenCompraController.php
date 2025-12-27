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
            $data = DB::table('tw_ordenes_compras AS T1')
                ->leftJoin('tr_ordenes_compras_requisiciones_refacciones AS T2', 'T2.id_orden_compra', '=', 'T1.id_orden_compra')
                ->leftJoin('tr_requisiciones_refacciones AS T3', 'T3.id_requisicion_refaccion', '=', 'T2.id_requisicion_refaccion')
                ->leftJoin('tw_refacciones AS T4', 'T4.id_refaccion', '=', 'T3.id_refaccion')
                ->leftJoin('tc_motivos_pedidos AS T5', 'T5.id_motivo_pedido', '=', 'T3.id_motivo_pedido')
                ->leftJoin('tc_prioridades AS T6', 'T6.id_prioridad', '=', 'T3.id_prioridad')
                ->select(
                    'T2.id_orden_compra_requisicion_refaccion',
                    'T4.s_nombre_refaccion',
                    'T4.s_numero_parte',
                    'T3.id_requisicion_refaccion',
                    'T3.n_cantidad_sugerida',
                    'T3.n_costo_unitario',
                    'T3.id_motivo_pedido',
                    'T5.s_motivo_pedido',
                    'T3.id_prioridad',
                    'T6.s_prioridad'
                )
                ->where('T1.id_orden_compra', $id_orden_compra)
                ->where('T1.b_activo', 1)
                ->where('T2.b_activo', 1)
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

    public function gestionarOrdenCompra(Request $request, $id_orden_compra)
    {
        try {
            $request->validate([
                'id_estatus_orden_compra' => 'required|integer|in:2,3', // 2=Aprobada, 3=Rechazada
                'refacciones' => 'required|array|min:0',
                'refacciones.*.id_requisicion_refaccion' => 'required|integer|exists:tr_requisiciones_refacciones,id_requisicion_refaccion',
                'refacciones.*.n_cantidad_solicitada' => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->validator->errors()->first()
            ], 500);
        }

        try {
            return DB::transaction(function () use ($request, $id_orden_compra) {

                $ordenCompra = DB::table('tw_ordenes_compras')
                    ->where('id_orden_compra', $id_orden_compra)
                    ->where('b_activo', 1)
                    ->first();

                if (!$ordenCompra) {
                    throw new \Exception("La orden de compra no existe o no está activa.");
                }

                // APROBADA
                if ($request->id_estatus_orden_compra == 2) {

                    $nuevoTotalOrden = 0;

                    foreach ($request->refacciones as $refaccionData) {

                        DB::table('tr_requisiciones_refacciones')
                            ->where('id_requisicion_refaccion', $refaccionData['id_requisicion_refaccion'])
                            ->update([
                                'n_cantidad_solicitada' => $refaccionData['n_cantidad_solicitada'],
                                //'id_estatus_requisicion' => 3 // sigue "En Orden de Compra"
                            ]);

                        // recalcular el total de la orden
                        $itemRequisicion = DB::table('tr_requisiciones_refacciones')
                            ->where('id_requisicion_refaccion', $refaccionData['id_requisicion_refaccion'])
                            ->select('n_costo_unitario')
                            ->first();

                        if ($itemRequisicion) {
                            $nuevoTotalOrden += ($refaccionData['n_cantidad_solicitada'] * $itemRequisicion->n_costo_unitario);
                        }
                    }

                    // Actualizar la de la Orden de Compra
                    DB::table('tw_ordenes_compras')
                        ->where('id_orden_compra', $id_orden_compra)
                        ->update([
                            'id_estatus_orden_compra' => 2, // Aprobada / Enviada
                            'n_total_estimado' => $nuevoTotalOrden,
                            // 'id_usuario_autoriza' => auth()->id()
                        ]);
                }

                // RECHAZADA
                elseif ($request->id_estatus_orden_compra == 3) {

                    // Actualizar a Cancelada
                    DB::table('tw_ordenes_compras')
                        ->where('id_orden_compra', $id_orden_compra)
                        ->update([
                            'id_estatus_orden_compra' => 3
                        ]);

                    // TODO Liberar las partidas en la tabla de requisiciones
                    // Al ponerlas en estatus "Cancelado" o regresarlas a "Pendiente",
                    // permitir'a que el sistema vuelva a detectar la falta de stock en el futuro.
                    // optar por marcar el item de la requisición como
                    // un estatus específico de rechazo, para que no queden bloqueadas.

                    $idsRequisicionRefaccion = collect($request->refacciones)->pluck('id_requisicion_refaccion');

                    DB::table('tr_requisiciones_refacciones')
                        ->whereIn('id_requisicion_refaccion', $idsRequisicionRefaccion)
                        ->update([
                            // de momento a estatus 4 (Cancelada) o null para indicar que ya no está atada a una OC activa
                            'id_estatus_requisicion' => 4
                        ]);
                }

                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Orden de compra actualizada correctamente.',
                ], 200);

            });

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generarOrdenCompraPDF($id_orden_compra)
    {
        try {
            // Obtener información (Orden + Proveedor + Estatus)
            $orden = DB::table('tw_ordenes_compras AS T1')
                ->leftJoin('tw_proveedores AS T2', 'T2.id_proveedor', '=', 'T1.id_proveedor')
                ->leftJoin('tc_estatus_ordenes_compras AS T3', 'T3.id_estatus_orden_compra', '=', 'T1.id_estatus_orden_compra')
                ->select(
                    'T1.id_orden_compra',
                    'T1.s_folio_interno',
                    'T1.d_fecha_orden',
                    'T1.d_fecha_recepcion_estimada',
                    'T1.s_observacion',
                    'T1.n_total_estimado',
                    'T2.s_proveedor',
                    'T2.id_proveedor', // RFC
                    'T3.s_estatus_orden_compra'
                )
                ->where('T1.id_orden_compra', $id_orden_compra)
                ->where('T1.b_activo', 1)
                ->first();

            if (!$orden) {
                throw new \Exception("La orden de compra no existe.");
            }

            // Obtener el Detalle (Pivot -> RequisicionDetalle -> Refaccion)
            $detalles = DB::table('tr_ordenes_compras_requisiciones_refacciones AS T1')
                ->join('tr_requisiciones_refacciones AS T2', 'T1.id_requisicion_refaccion', '=', 'T2.id_requisicion_refaccion')
                ->join('tw_refacciones AS T3', 'T2.id_refaccion', '=', 'T3.id_refaccion')
                ->select(
                    'T3.s_nombre_refaccion',
                    'T3.s_numero_parte',
                    'T3.s_sku',
                    'T2.n_cantidad_solicitada',
                    'T2.n_costo_unitario'
                )
                ->where('T1.id_orden_compra', $id_orden_compra)
                ->where('T1.b_activo', 1)
                ->get();

            // Generar el PDF usando la vista Blade
            //  resources/views/ordenes-compras.orden_compra.blade.php
            $pdf = \PDF::loadView('ordenes-compras.orden_compra', [
                'orden' => $orden,
                'detalles' => $detalles
            ]);

            // Configuración de papel (Carta vertical es estándar)
            $pdf->setPaper('letter', 'portrait');

            // Convertir a Base64
            $pdfBase64 = base64_encode($pdf->output());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'PDF generado correctamente.',
                'data' => [
                    'folio' => $orden->s_folio_interno,
                    'file_base64' => $pdfBase64
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al generar el PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
