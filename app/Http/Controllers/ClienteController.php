<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function getClientes()
    {
        try {
            $data = DB::table('tw_clientes AS T1')
                ->select(
                    'T1.id_cliente',
                    'T1.s_nombre_cliente',
                    DB::raw('(T1.n_limite_credito - T1.n_saldo_actual) as saldo_actual')
                )
                ->where('T1.b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay categorias disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Categorias de modulos obtenidas correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener categorias de modulos',
                'error' => $e->getMessage()
            ];
        }
    }
}
