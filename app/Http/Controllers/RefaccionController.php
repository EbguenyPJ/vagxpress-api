<?php

namespace App\Http\Controllers;

use App\Models\Refaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefaccionController extends Controller
{
    public function crearRefaccion(Request $request)
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
                'status' => 'error',
                'code' => 422,
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
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Operación realizada correctamente.',
                    'data' => [], // Puedes devolver el modelo creado o cualquier data
                ], 200);
            });

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            // Error general
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mostrarRefacciones()
    {

        // 2. Lógica en base de datos dentro de transacción
        try {
                $data = DB::table('tw_refacciones AS T1')
                    ->leftJoin('tc_marcas_refacciones AS T2', 'T1.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                    ->leftJoin('tc_unidades_medida AS T3', 'T1.id_unidad_medida', '=', 'T3.id_unidad_medida')
                    ->leftJoin('tw_provedores AS T4', 'T1.id_provedor', '=', 'T4.id_provedor')
                    ->leftJoin('tc_categorias_refacciones AS T5', 'T1.id_categoria_refaccion', '=', 'T5.id_categoria_refaccion')
                    ->leftJoin('tc_subcategorias_refacciones AS T6', 'T1.id_subcategoria_refaccion', '=', 'T6.id_subcategoria_refaccion')
                    ->leftJoin('tc_posiciones_vehiculo AS T7', 'T1.id_posicion_vehiculo', '=', 'T7.id_posicion_vehiculo')
                    ->leftJoin('tc_ubicaciones_almacen AS T8', 'T1.id_ubicacion_almacen', '=', 'T8.id_ubicacion_almacen')
                    ->leftJoin('tc_estatus_refacciones AS T9', 'T1.id_estatus_refaccion', '=', 'T9.id_estatus_refaccion')

                    ->select(
                        'T1.id_refaccion',
                        'T1.s_nombre_refaccion',
//                        'T1.s_descripcion',
                        'T1.s_numero_parte',
//                        'T1.n_precio_compra',
                        'T1.n_precio_venta',
                        'T2.s_marca_refaccion',
//                        'T4.s_provedor',
                        'T5.s_categoria_refaccion',
                        'T6.s_subcategoria_refaccion',
                        'T1.s_imagen_refaccion',
                        'T1.n_stock_actual',
//                        'T7.s_posicion_vehiculo',
//                        'T8.s_ubicacion_almacen',
                        'T9.s_estatus_refaccion',
                    )
                    ->where('b_activo', 1)
                    ->get();

                // Respuesta de éxito
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Operación realizada correctamente.',
                    'data' => $data,
                ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            // Error general
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function mostrarRefaccionId($id_refaccion)
    {
        try {
            $refaccion = Refaccion::find($id_refaccion);
            if (!$refaccion) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se encontro refacción para este ID.',
                ], 404);
            }

            $data = DB::table('tw_refacciones AS T1')
                ->leftJoin('tc_marcas_refacciones AS T2', 'T1.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                ->leftJoin('tc_unidades_medida AS T3', 'T1.id_unidad_medida', '=', 'T3.id_unidad_medida')
                ->leftJoin('tw_provedores AS T4', 'T1.id_provedor', '=', 'T4.id_provedor')
                ->leftJoin('tc_categorias_refacciones AS T5', 'T1.id_categoria_refaccion', '=', 'T5.id_categoria_refaccion')
                ->leftJoin('tc_subcategorias_refacciones AS T6', 'T1.id_subcategoria_refaccion', '=', 'T6.id_subcategoria_refaccion')
                ->leftJoin('tc_posiciones_vehiculo AS T7', 'T1.id_posicion_vehiculo', '=', 'T7.id_posicion_vehiculo')
                ->leftJoin('tc_ubicaciones_almacen AS T8', 'T1.id_ubicacion_almacen', '=', 'T8.id_ubicacion_almacen')
                ->leftJoin('tc_estatus_refacciones AS T9', 'T1.id_estatus_refaccion', '=', 'T9.id_estatus_refaccion')
                ->select(
                    'T1.id_refaccion',
                    'T1.s_nombre_refaccion',
                    'T1.s_descripcion',
                    'T1.s_numero_parte',
                    'T1.s_codigo_interno',
                    'T1.s_imagen_refaccion',
                    'T1.n_precio_compra',
                    'T1.n_precio_venta',
                    'T1.n_precio_mayoreo',
                    'T1.n_stock_actual',
                    'T1.n_stock_minimo',
                    'T1.n_tiempo_reposicion',
                    'T2.s_marca_refaccion',
                    'T3.s_unidad_medida',
                    'T4.s_provedor',
                    'T5.s_categoria_refaccion',
                    'T6.s_subcategoria_refaccion',
                    'T7.s_posicion_vehiculo',
                    'T8.s_ubicacion_almacen',
                    'T9.s_estatus_refaccion',
                    'T1.b_importado',
                )
                ->where('id_refaccion', $id_refaccion)
                ->where('b_activo', 1)
                ->first();


            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Operación realizada correctamente.',
                'data'    => $data,
            ], 200);

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
