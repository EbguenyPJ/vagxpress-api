<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatalogoController extends Controller
{
    /**
     * Obtener todos los catálogos activos
     */
    public function getAll()
    {
        try {
            $catalogos = DB::table('tc_catalogos as T1')
                ->where('T1.b_activo', 1)
                ->select(
                    'T1.id_catalogo',
                    'T1.s_catalogo',
                    'T1.s_nombre_table',
                    'T1.b_funciones_activas',
                    'T1.b_activo'
                )
                ->orderBy('T1.s_catalogo', 'asc')
                ->get();

            if ($catalogos->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se encontraron catálogos activos',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Catálogos obtenidos correctamente',
                'data' => $catalogos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener los catálogos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un catálogo específico por ID
     */
    public function getById($id)
    {
        try {
            $catalogo = DB::table('tc_catalogos as T1')
                ->where('T1.id_catalogo', $id)
                ->where('T1.b_activo', 1)
                ->select(
                    'T1.id_catalogo',
                    'T1.s_catalogo',
                    'T1.s_nombre_table',
                    'T1.b_funciones_activas',
                    'T1.b_activo'
                )
                ->first();

            if (!$catalogo) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Catálogo no encontrado o inactivo',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Catálogo obtenido correctamente',
                'data' => $catalogo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener el catálogo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo catálogo
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            's_catalogo' => 'required|string|max:255',
            's_nombre_table' => 'required|string|max:255',
            'b_funciones_activas' => 'required|boolean',
            'b_activo' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $idCatalogo = DB::table('tc_catalogos')->insertGetId([
                's_catalogo' => $request->s_catalogo,
                's_nombre_table' => $request->s_nombre_table,
                'b_funciones_activas' => $request->b_funciones_activas,
                'b_activo' => $request->b_activo ?? 1
            ]);

            $catalogo = DB::table('tc_catalogos')
                ->where('id_catalogo', $idCatalogo)
                ->first();

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Catálogo creado correctamente',
                'data' => $catalogo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al crear el catálogo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un catálogo existente
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            's_catalogo' => 'sometimes|string|max:255',
            's_nombre_table' => 'sometimes|string|max:255',
            'b_funciones_activas' => 'sometimes|boolean',
            'b_activo' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $updateData = array_filter([
                's_catalogo' => $request->s_catalogo,
                's_nombre_table' => $request->s_nombre_table,
                'b_funciones_activas' => $request->b_funciones_activas,
                'b_activo' => $request->b_activo
            ], function ($value) {
                return !is_null($value);
            });

            $affected = DB::table('tc_catalogos')
                ->where('id_catalogo', $id)
                ->update($updateData);

            if ($affected === 0) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Catálogo no encontrado o sin cambios',
                ], 404);
            }

            $catalogo = DB::table('tc_catalogos')
                ->where('id_catalogo', $id)
                ->first();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Catálogo actualizado correctamente',
                'data' => $catalogo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al actualizar el catálogo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar un catálogo (borrado lógico)
     */
    public function deactivate($id)
    {
        try {
            $affected = DB::table('tc_catalogos')
                ->where('id_catalogo', $id)
                ->where('b_activo', 1)
                ->update(['b_activo' => 0]);

            if ($affected === 0) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Catálogo no encontrado o ya inactivo',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Catálogo desactivado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al desactivar el catálogo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
