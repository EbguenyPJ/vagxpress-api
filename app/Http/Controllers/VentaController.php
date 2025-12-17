<?php

namespace App\Http\Controllers;

use App\Models\Credito;
use App\Models\Equivalencia;
use App\Models\PorcentajeUtilidad;
use App\Models\Refaccion;
use App\Models\RefaccionEquivalencia;
use App\Models\Venta;
use App\Models\VentaRefaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getVentas()
    {
        try {
            $data = DB::table('tw_ventas AS T1')
                ->leftJoin('tc_estatus_ventas AS T2', 'T2.id_estatus_venta', '=', 'T1.id_estatus_venta')
                ->leftJoin('tc_metodos_pagos AS T3', 'T3.id_metodo_pago', '=', 'T1.id_metodo_pago')
                ->leftJoin('tw_clientes AS T4', 'T4.id_cliente', '=', 'T1.id_cliente')
                ->select(
                    'T1.id_venta',
                    'T1.id_venta',
                    'T1.n_subtotal',
                    'T1.n_porcentaje_iva',
                    'T1.n_total',
                    'T1.n_cantidad_refacciones',
                    'T1.id_estatus_venta',
                    'T2.s_estatus_venta',
                    'T1.id_metodo_pago',
                    'T3.s_metodo_pago',
                    'T1.id_cliente',
                    'T4.s_nombre_cliente',
                )
                ->where('T1.b_activo', 1)
                ->orderBy('id_venta', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay ventas disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Porcentajes de utilidad obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener los porcentajes de utilidad',
                'error' => $e->getMessage()
            ];
        }
    }
    public function crearVenta(Request $request)
    {
        try {
            $request->validate([
                'id_cliente' => 'required|numeric|exists:tw_clientes,id_cliente',
                'id_usuario_crea' => 'required|numeric|exists:users,id',
                'id_metodo_pago' => 'required|numeric|exists:tc_metodos_pagos,id_metodo_pago',

                'refacciones' => 'nullable|array',
                'refacciones.*.n_cantidad' => 'required|numeric|min:1',
                'refacciones.*.id_porcentaje_utilidad' => 'nullable|numeric|min:1|exists:tc_porcentajes_utilidad,id_porcentaje_utilidad',
                'refacciones.*.id_refaccion' => 'nullable|numeric|min:1',




//                'n_subtotal',
//                'n_porcentaje_iva',
//                'n_total',
//                'n_cantidad_refacciones',
//                'id_estatus_venta',
//                'id_cliente',
//                'id_usuario_crea',
//                'id_usuario_modifica',




//                'n_cantidad',
//                'n_costo_unitario',
//                'n_porcentaje_utilidad',
//                'n_total',
//                'n_stock_previo',
//                'n_stock_posterior',
//                'id_venta',
//                'id_refaccion',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => [$e->errors()],
            ], 422);
        }

        try {
            \DB::beginTransaction();











            $nuevaVenta = new Venta();
            $nuevaVenta->id_estatus_venta              =   1;
            $nuevaVenta->id_cliente                  =   $request->id_cliente;
            $nuevaVenta->id_usuario_crea             =   $request->id_usuario_crea;
            $nuevaVenta->id_metodo_pago             =   $request->id_metodo_pago;
            $nuevaVenta->save();


            $datosRefacciones = $request->input('refacciones');

            $count = 0;
            $subtotalRefacciones = 0;
            $detalleCreado= [];
            foreach ($datosRefacciones as $dataRefaccion) {

                $refaccion = Refaccion::findOrFail($dataRefaccion['id_refaccion']);

                $utilidad = 1;
                $porcentajeUtilidad = null;
                if($dataRefaccion['id_porcentaje_utilidad'] !== null){
                    $porcentajeUtilidad = PorcentajeUtilidad::findOrFail($dataRefaccion['id_porcentaje_utilidad']);
                    $utilidad += $porcentajeUtilidad->porcentaje_utilidad / 100;
                }

                $nuevaVentaRefaccion = new VentaRefaccion();
                $nuevaVentaRefaccion->n_cantidad                =   $dataRefaccion['n_cantidad'];
                $nuevaVentaRefaccion->n_costo_unitario          =   $refaccion->n_precio_venta;
                $nuevaVentaRefaccion->n_porcentaje_utilidad     =   $porcentajeUtilidad->n_porcentaje_utilidad?? null;
                $nuevaVentaRefaccion->n_total                   =   $dataRefaccion['n_cantidad'] * $refaccion->n_precio_venta * $utilidad;
                $nuevaVentaRefaccion->n_stock_previo            =   $refaccion->n_stock_actual;
                $nuevaVentaRefaccion->n_stock_posterior         =   $refaccion->n_stock_actual - $dataRefaccion['n_cantidad'];
                $nuevaVentaRefaccion->id_venta                  =   $nuevaVenta->id_venta;
                $nuevaVentaRefaccion->id_refaccion              =   $dataRefaccion['id_refaccion'];
                $nuevaVentaRefaccion->save();



                // Agrega el nombre para usarlo en el PDF sin hacer otra consulta
                $detalleParaTicket = $nuevaVentaRefaccion->toArray();
                $detalleParaTicket['nombre_refaccion'] = $refaccion->s_nombre_refaccion; // O como se llame el campo nombre

                $detalleCreado[] = $detalleParaTicket;



                $count++;
                $subtotalRefacciones+=$nuevaVentaRefaccion['n_total'];
            }


            $nuevaVenta->n_subtotal                  =   $subtotalRefacciones;
            //$nuevaVenta->n_porcentaje_iva            =   $request->n_porcentaje_iva;
            $nuevaVenta->n_porcentaje_iva            =   16.00;
            //$nuevaVenta->n_total                     =   calclar en base al iva de configuraciones;
            $nuevaVenta->n_total                     =   $subtotalRefacciones * 1.16;
            $nuevaVenta->n_cantidad_refacciones      =   $count;
            $nuevaVenta->save();

            if($request->id_metodo_pago == 1){
                $nuevoCredito = new Credito();
                $nuevoCredito->id_venta = $nuevaVenta->id_venta;
                $nuevoCredito->n_total_a_pagar = $nuevaVenta->n_total;
                $nuevoCredito->id_tipo_credito = 1;
                $nuevoCredito->id_estatus_credito = 1;
                $nuevoCredito->id_usuario_crea = $request->id_usuario_crea;
                $nuevoCredito->save();

                if($request->id_cliente !== 1){
                    DB::table('tw_clientes')
                        ->where('id_cliente', $request->id_cliente)
                        ->increment('n_saldo_actual', $nuevaVenta->n_total);
                }
            }






            \DB::commit();






            $clienteModel = \DB::table('tw_clientes')->where('id_cliente', $request->id_cliente)->first();

            $pdf = \PDF::loadView('tickets.venta_pos', [
                'venta' => $nuevaVenta,
                'detalles' => $detalleCreado,
                'cliente' => $clienteModel, // <--- Pasamos el objeto
                'credito' => isset($nuevoCredito) ? $nuevoCredito : null
            ]);

            // Esto es CRUCIAL para tickets térmicos largos
            // [0, 0, ancho_puntos, largo_puntos]
            // 226 puntos son aprox 80mm.
            // El largo (1000) puede ser variable si usas papel continuo, pero ponle un maximo alto.
            $pdf->setPaper([0, 0, 226, 1000], 'portrait');

            // 3. Convertir a Base64
            $ticketBase64 = base64_encode($pdf->output());

            // 4. Retornar JSON incluyendo el ticket
            return response()->json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Venta creada correctamente.',
                'data'    => $detalleCreado,
                'ticket_base64' => $ticketBase64 // <--- Agregamos esto
            ]);






//            return response()->json([
//                'status'  => 'success',
//                'code'    => 201,
//                'message' => 'Refacción creada correctamente.',
//                'data'    => $detalleCreado,
//            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al crear la refacción.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
