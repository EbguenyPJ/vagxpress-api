<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class SucursalController extends Controller
{
     public function getAll()
    {
        try {
            $estatus = DB::table('tw_sucursales as T1')
                ->leftjoin('tc_estados_republica AS T2', 'T2.id_estado_republica', 'T1.id_estado_republica')
                ->leftjoin('tc_municipios AS T3', 'T3.id_municipio', 'T1.id_municipio')
                ->where('T1.b_activo', 1)
                ->get();

            if ($estatus->isEmpty()) {
                // Respuesta de error
                return response()->json([
                    'status' => 'error',
                    'code'   => 400,
                    'message' => 'No hay datos en el catalogo de Sucursales',
                ], 400);
            }

            // Respuesta de exito
            return response()->json([
                'status'    => 'success',
                'code'      => 200,
                'message' => 'Catalogo de Sucursales obtenido correctamente',
                'data'      => $estatus
            ], 200);

        } catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code'   => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
