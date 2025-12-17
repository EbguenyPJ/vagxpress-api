<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TiposClientesController extends Controller
{
    public function getAll()
{
    try {
        $data = DB::table('tc_tipos_clientes AS T1')
            ->select(
                'T1.id_tipo_cliente',
                'T1.s_tipo_cliente',
                'T1.b_activo'
            )
            ->where('T1.b_activo', 1)
            ->get();

        if ($data->isEmpty()) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'No hay tipos de clientes disponibles'
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Tipos de clientes obtenidos correctamente',
            'data' => $data
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Error al obtener tipos de clientes',
            'error' => $e->getMessage()
        ];
    }
}

}
