<?php

namespace App\Http\Controllers\Catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProfesionesController extends Controller
{
    public function getAll()
    {
        try {
            $estatus = DB::table('tc_profesiones')
                ->where('b_activo', 1)
                ->get();

            if ($estatus->isEmpty()) {
                // Respuesta de error
                return response()->json([
                    'status' => 'error',
                    'code'   => 400,
                    'message' => 'No hay datos en el catalogo de Profesiones',
                ], 400);
            }

            // Respuesta de exito
            return response()->json([
                'status'    => 'success',
                'code'      => 200,
                'message' => 'Catalogo de Profesiones obtenido correctamente',
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
