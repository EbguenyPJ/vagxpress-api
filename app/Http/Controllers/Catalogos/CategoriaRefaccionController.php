<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CategoriaRefaccion;
use Illuminate\Http\Request;

class CategoriaRefaccionController extends Controller
{
    public function nombreFuncion(Request $request)
    {
        // 1. Validación de datos
        try {
            $request->validate(
                [
                    // Reglas de validación
                    // 'campo' => 'required|string',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 422,
                'message' => $e->validator->errors()->first(),
            ], 422);
        }

        // 2. Lógica en base de datos dentro de transacción
        try {
            return \DB::transaction(function () use ($request) {

                // 👉 Aquí va tu lógica de negocio
                // Ejemplo:
                // $modelo = new Modelo();
                // $modelo->campo = $request->campo;
                // $modelo->save();

                // Respuesta de éxito
                return response()->json([
                    'status'  => 'success',
                    'code'    => 200,
                    'message' => 'Operación realizada correctamente.',
                    'data'    => [], // Puedes devolver el modelo creado o cualquier data
                ], 200);
            });

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            // Error general
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
