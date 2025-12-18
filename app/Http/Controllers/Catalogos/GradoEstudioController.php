<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GradoEstudioController extends Controller
{
     public function getAll()
    {
        try {
            $estatus = DB::table('tc_grados_estudios')
                ->where('b_activo', 1)
                ->get();

            if ($estatus->isEmpty()) {
                // Respuesta de error
                return response()->json([
                    'status' => 'error',
                    'code'   => 400,
                    'message' => 'No hay datos en el catalogo de Grados estudios',
                ], 400);
            }

            // Respuesta de exito
            return response()->json([
                'status'    => 'success',
                'code'      => 200,
                'message' => 'Catalogo de Grados estudios obtenido correctamente',
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
