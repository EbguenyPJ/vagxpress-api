<?php

namespace App\Http\Controllers;

use App\Models\PorcentajeUtilidad;
use App\Models\Refaccion;
use App\Models\Cotizacion;
use App\Models\CotizacionRefaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function crearCotizacion(Request $request)
    {
        try {
            $request->validate([
                'id_usuario' => 'required|numeric|exists:users,id',
                'id_cliente' => 'required|numeric|exists:tw_clientes,id_cliente',

                'refacciones' => 'nullable|array',
                'refacciones.*.n_cantidad' => 'required|numeric|min:1',
                'refacciones.*.id_porcentaje_utilidad' => 'nullable|numeric|min:1|exists:tc_porcentajes_utilidad,id_porcentaje_utilidad',
                'refacciones.*.id_refaccion' => 'nullable|numeric|min:1',

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




            $nuevaCotizacion = new Cotizacion();
            $nuevaCotizacion->id_estatus_cotizacion             =   1;
            $nuevaCotizacion->id_tipo_cotizacion             =   1;
            $nuevaCotizacion->id_cliente                  =   $request->id_cliente;
            $nuevaCotizacion->id_usuario_crea             =   $request->id_usuario;
            $nuevaCotizacion->save();


            $datosRefacciones = $request->input('refacciones');

            $count = 0;
            $subtotalRefacciones = 0;
            $detalleCreado = [];
            foreach ($datosRefacciones as $dataRefaccion) {

                $refaccion = Refaccion::findOrFail($dataRefaccion['id_refaccion']);

                $utilidad = 1;
                $porcentajeUtilidad = null;
                if ($dataRefaccion['id_porcentaje_utilidad'] !== null) {
                    $porcentajeUtilidad = PorcentajeUtilidad::findOrFail($dataRefaccion['id_porcentaje_utilidad']);
                    $utilidad += $porcentajeUtilidad->porcentaje_utilidad / 100;
                }



                $nuevaCotizacionRefaccion = new CotizacionRefaccion();
                $nuevaCotizacionRefaccion->n_cantidad                       =   $dataRefaccion['n_cantidad'];
                $nuevaCotizacionRefaccion->n_costo_unitario                 =   $refaccion->n_precio_venta;
                $nuevaCotizacionRefaccion->n_porcentaje_utilidad            =   $porcentajeUtilidad->n_porcentaje_utilidad ?? null;
                $nuevaCotizacionRefaccion->n_total                          =   $dataRefaccion['n_cantidad'] * $refaccion->n_precio_venta * $utilidad;
                $nuevaCotizacionRefaccion->id_cotizacion                    =   $nuevaCotizacion->id_cotizacion;
                $nuevaCotizacionRefaccion->id_refaccion                     =   $dataRefaccion['id_refaccion'];
                $nuevaCotizacionRefaccion->save();



                $count++;
                $subtotalRefacciones += $nuevaCotizacionRefaccion['n_total'];
            }



            $nuevaCotizacion->n_subtotal                  =   $subtotalRefacciones;
            //$nuevaCotizacion->n_porcentaje_iva            =   $request->n_porcentaje_iva;
            $nuevaCotizacion->n_porcentaje_iva            =   16.00;
            //$nuevaCotizacion->n_total                     =   calclar en base al iva de configuraciones;
            $nuevaCotizacion->n_total                     =   $subtotalRefacciones * 1.16;
            $nuevaCotizacion->n_cantidad_refacciones      =   $count;
            $nuevaCotizacion->save();


            \DB::commit();



            return response()->json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Cotizacion creada correctamente.',
                'data'    => $detalleCreado,
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
