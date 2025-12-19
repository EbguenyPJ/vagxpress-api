<?php

namespace App\Http\Controllers\catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\User;

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


    public function actualizarTipoUsuario(Request $request, $id_usuario)
{
    try {
        $user = User::find($id_usuario);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        // Validar que el id_tipo_usuario sea un entero positivo
        $request->validate([
            'id_tipo_usuario' => 'required|integer|min:1',
        ]);

        // Actualizar el tipo de usuario
        $user->id_tipo_usuario = $request->id_tipo_usuario;
        $user->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Tipo de usuario actualizado correctamente',
            'data' => [
                'id_tipo_usuario' => $user->id_tipo_usuario,
            ],
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'code' => 500,
            'message' => 'Error al actualizar el tipo de usuario',
            'error' => $e->getMessage(),
        ], 500);
    }      
}




}
