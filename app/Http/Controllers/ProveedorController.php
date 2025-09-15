<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function getProveedores()
    {
        try {
            $data = DB::table('tw_proveedores')
                ->select(
                    '*'
                )
                ->where('b_activo', 1)
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
