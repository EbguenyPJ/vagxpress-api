<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TipoUsuarioController extends Controller
{
    public function getAll()
    {
        try{
            $tiposServicio = DB::table('tc_tipos_usuarios')
                ->where('b_activo', 1)
                ->get();

            if ($tiposServicio->isEmpty()) {
                // Respuesta de error
                return response()->json([
                    'status' => 'error',
                    'code'   => 400,
                    'message' => 'No hay datos en el catalogo de Tipos de usuarios',
                ], 400);
            }

            // Respuesta de exito
            return response()->json([
                'status'    => 'success',
                'code'      => 200,
                'message' => 'Catalogo de Tipos de usuarios obtenido correctamente',
                'data'      => $tiposServicio
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
