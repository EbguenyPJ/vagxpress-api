<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\EvidenciaOrden;
use App\Helpers\ComprimirImagenHelper;

class RepartidorController extends Controller
{
    //
    public function getOrdenesAsignadas($id_repartidor){
        try{

            $ordenes = DB::table('tw_ordenes as T1')
                ->leftjoin('tw_destinos as T2', 'T2.id_destino', '=', 'T1.id_destino')
                ->leftjoin('tc_estatus_orden as T3', 'T3.id_estatus_orden', '=', 'T1.id_estatus_orden')
                ->select(
                    'T1.id_orden',
                    'T1.id_destino',
                    'T2.s_nombre_destino',
                    'T2.s_direccion',
                    'T2.s_referencia_destino',
                    'T1.s_nota_refaccionista',
                    'T1.id_repartidor',
                    'T1.d_fecha_asignacion',
                    'T1.id_estatus_orden',
                    'T3.s_estatus_orden',
                    'T1.d_fecha_entrega',
                )
                ->where('T1.b_activo', 1)
                ->where('T1.id_estatus_orden', 1)
                ->get();


            // Respuesta de éxito
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $ordenes,
                'message' => 'Ordenes asignadas'
            ], 200);


        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getDetalleOrden($id_orden){
        try{

            $orden = DB::table('tw_ordenes as T1')
                ->leftjoin('tw_destinos as T2', 'T2.id_destino', '=', 'T1.id_destino')
                ->leftjoin('tc_estatus_orden as T3', 'T3.id_estatus_orden', '=', 'T1.id_estatus_orden')
                ->select(
                    'T1.id_orden',
                    'T1.id_destino',
                    'T2.s_nombre_destino',
                    'T2.s_direccion',
                    'T2.s_referencia_destino',
                    'T1.s_nota_refaccionista',
                    'T1.id_repartidor',
                    'T1.d_fecha_asignacion',
                    'T1.id_estatus_orden',
                    'T3.s_estatus_orden',
                    'T1.d_fecha_entrega',
                )
                ->where('T1.b_activo', 1)
                ->where('T1.id_estatus_orden', 1)
                ->where('T1.id_orden', $id_orden)
                ->get();


            $productos = DB::table('tr_ordenes_productos as T1')
                ->select(
                    'T1.id_orden_producto',
                    'T1.id_orden',
                    'T1.s_producto',
                    'T1.n_cantidad',
                    'T1.s_comentario'
                )
                ->where('T1.b_activo', 1)
                ->get()
                ->groupBy('id_orden');


            $orden->transform(function ($srv) use ($productos) {
                $srv->productos = $productos[$srv->id_orden] ?? [];
                return $srv;
            });

            // Respuesta de éxito
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $orden,
                'message' => 'Detalle de orden'
            ], 200);

        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function subirEvidenciasSalidaReparto(Request $request){
        try{
            return DB::transaction(function () use ($request) {

                if($request->evidencias){
                    foreach($request->evidencias as $evidencia){
                        $evidenciaOrden = new EvidenciaOrden();
                        $evidenciaOrden->id_orden = $request->id_orden;
                        $evidenciaOrden->id_tipo_evidencia = 4;
                        $evidenciaOrden->s_evidencia_orden = 'temp_name';
                        $evidenciaOrden->b_activo = 1;
                        $evidenciaOrden->save();


                        $imagenBase64 = $evidencia['imagen'];
                        $extension = 'jpg';
                        $nombreFinal = 'PSR_OS' . $request->id_orden . '_TE4_' . $evidenciaOrden->id_evidencia_orden . '.' . $extension;
                        $carpeta = 'evidenciasVXM/imgEvidenciasSalidaReparto';
                        // llamar al servicio del helper
                        $comprimir = ComprimirImagenHelper::comprimirImagenBase64(
                            $imagenBase64, // imagen
                            $carpeta,      // carpeta
                            $nombreFinal,  // nombre de la imagen 
                        );
                        // Validar si la imagen se creó correctamente
                        if ($comprimir['status'] === 'success') {
                            // Continuar el flujo normal
                            $evidenciaOrden->s_evidencia_orden = $nombreFinal;
                            $evidenciaOrden->save();
                        }
                        else{
                            return response()->json([
                                'status' => 'error',
                                'code' => 400,
                                'message' => 'Hubo un error al comprimir la imagen'
                            ], 400);
                        }
                    }
                }


                // Respuesta de éxito
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Evidencias subidas exitosamente.'
                ], 200);

            });
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
