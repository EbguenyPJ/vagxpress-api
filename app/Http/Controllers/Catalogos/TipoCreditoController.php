<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoCreditoController extends Controller
{
    public function getTiposCreditos()
    {
        try {
            $data = DB::table('tc_tipos_creditos AS T1')
                ->select(
                    'T1.id_tipo_credito',
                    'T1.s_tipo_credito',
                )
                ->where('b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay tipos de creditos disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Tipos de Creditos obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener los Tipos de Creditos',
                'error' => $e->getMessage()
            ];
        }
    }
}
