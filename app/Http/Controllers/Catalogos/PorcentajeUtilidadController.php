<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PorcentajeUtilidadController extends Controller
{
    public function getPorcentajesUtilidad()
    {
        try {
            $data = DB::table('tc_porcentajes_utilidad AS T1')
                ->select(
                    'T1.id_porcentaje_utilidad',
                    'T1.n_porcentaje_utilidad',
                    'T1.id_tipo_configuracion',
                )
                ->whereIn('id_tipo_configuracion', [2,3])
                ->where('b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay porcentajes disponibles',
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
}
