<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloController extends Controller
{
    public function getModulosUsuario($id_usuario)
    {
        try {
            $data = DB::table('tr_modulos_usuarios AS T1')
                ->leftJoin('tc_modulos AS T2', 'T1.id_modulo', '=', 'T2.id_modulo')
                ->leftJoin('tc_categorias_modulos AS T3', 'T2.id_categoria_modulo', '=', 'T3.id_categoria_modulo')
                ->select(
                    'T1.id_modulo',
                    'T1.id_usuario',
                    'T2.id_categoria_modulo',
                    'T3.s_categoria_modulo',
                    'T2.s_modulo',
                    'T2.s_ruta',
                    'T2.s_icono',
                )
                ->where('T1.id_usuario', $id_usuario)
                ->where('T1.b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay modulos asignados a este usuario',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Modulos del usuario obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener modulos del usuario',
                'error' => $e->getMessage()
            ];
        }
    }


    public function getAllModulos()
    {
        try {
            $data = DB::table('tc_modulos AS T2')
                ->leftJoin('tc_categorias_modulos AS T3', 'T2.id_categoria_modulo', '=', 'T3.id_categoria_modulo')
                ->select(
                    'T2.id_modulo',
                    'T2.id_categoria_modulo',
                    'T3.s_categoria_modulo',
                    'T2.s_modulo',
                    'T2.s_ruta',
                    'T2.s_icono',
                )
                ->where('T2.b_activo', 1)
                ->where('T3.b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay módulos disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Módulos obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener módulos',
                'error' => $e->getMessage()
            ];
        }
    }




    public function actualizarModulosUsuario(Request $request, $id_usuario)
    {
        try {
            // Validación del formato de módulos
            if (!$request->has('modulos') || !is_array($request->modulos)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'El formato de módulos es incorrecto',
                ], 400);
            }

            DB::beginTransaction();

            // 1. Desactivar todos los módulos actuales del usuario
            DB::table('tr_modulos_usuarios')
                ->where('id_usuario', $id_usuario)
                ->update(['b_activo' => 0]);

            // 2. Activar los módulos recibidos en el request
            foreach ($request->modulos as $id_modulo) {
                // Verificar si la relación ya existe
                $existe = DB::table('tr_modulos_usuarios')
                    ->where('id_usuario', $id_usuario)
                    ->where('id_modulo', $id_modulo)
                    ->exists();

                if ($existe) {
                    // Si existe, actualizar a activo
                    DB::table('tr_modulos_usuarios')
                        ->where('id_usuario', $id_usuario)
                        ->where('id_modulo', $id_modulo)
                        ->update(['b_activo' => 1]);
                } else {
                    // Si no existe, crear nuevo registro
                    DB::table('tr_modulos_usuarios')->insert([
                        'id_usuario' => $id_usuario,
                        'id_modulo' => $id_modulo,
                        'b_activo' => 1
                    ]);
                }
            }

            DB::commit();

            // Obtener y devolver los módulos actualizados del usuario
            $modulosActualizados = DB::table('tr_modulos_usuarios AS T1')
                ->join('tc_modulos AS T2', 'T1.id_modulo', '=', 'T2.id_modulo')
                ->join('tc_categorias_modulos AS T3', 'T2.id_categoria_modulo', '=', 'T3.id_categoria_modulo')
                ->select(
                    'T1.id_modulo',
                    'T1.id_usuario',
                    'T2.id_categoria_modulo',
                    'T3.s_categoria_modulo',
                    'T2.s_modulo',
                    'T2.s_ruta',
                    'T2.s_icono'
                )
                ->where('T1.id_usuario', $id_usuario)
                ->where('T1.b_activo', 1)
                ->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Módulos actualizados correctamente',
                'data' => $modulosActualizados
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al actualizar módulos del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
}
