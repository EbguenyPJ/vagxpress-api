<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Orden;

class AsignacionController extends Controller
{
    //
    public function getAllOrdenesPendientes(){
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
                    'T1.id_estatus_orden',
                    'T3.s_estatus_orden',
                    'T1.d_fecha_entrega',
                )
                ->where('T1.b_activo', 1)
                ->where('T1.id_estatus_orden', 4)
                ->get();


            // Respuesta de éxito
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $data,
                'message' => 'Ordenes pendientes.'
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
}
