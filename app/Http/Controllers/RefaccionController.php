<?php

namespace App\Http\Controllers;

use App\Models\Equivalencia;
use App\Models\Refaccion;
use App\Models\RefaccionEquivalencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RefaccionController extends Controller
{
    public function crearRefaccion(Request $request)
    {
        try {
            $request->validate([
                's_nombre_refaccion' => 'required|string|max:255|unique:tw_refacciones,s_nombre_refaccion',
                's_numero_parte' => 'nullable|string|max:255|unique:tw_refacciones,s_numero_parte',
                's_imagen_refaccion' => 'nullable|string|max:255',
                'n_precio_compra' => 'nullable|numeric|min:0',
                'n_precio_venta' => 'nullable|numeric|min:0',
                'n_stock_actual' => 'nullable|numeric|min:0',
                'id_marca_refaccion' => 'nullable|integer|exists:tc_marcas_refacciones,id_marca_refaccion',
                'id_unidad_medida' => 'nullable|integer|exists:tc_unidades_medida,id_unidad_medida',
                'id_proveedor' => 'nullable|integer|exists:tw_proveedores,id_proveedor',
                'id_clase_refaccion' => 'nullable|integer|exists:tc_clases_refacciones,id_clase_refaccion',
                'id_categoria_refaccion' => 'nullable|integer|exists:tc_categorias_refacciones,id_categoria_refaccion',
                'id_subcategoria_refaccion' => 'nullable|integer|exists:tc_subcategorias_refacciones,id_subcategoria_refaccion',
                'id_posicion_vehiculo' => 'nullable|integer|exists:tc_posiciones_vehiculo,id_posicion_vehiculo',
                'id_ubicacion_almacen' => 'nullable|integer|exists:tc_ubicaciones_almacen,id_ubicacion_almacen',
                'b_importado' => 'nullable|boolean',
                'id_usuario_crea' => 'nullable|integer|exists:users,id',
                'refacciones_equivalentes' => 'nullable|array',
                'refacciones_equivalentes.*' => 'integer|exists:tw_refacciones,id_refaccion',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => [$e->errors()],
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $utilidadBase = DB::table('tc_tipos_configuraciones AS T1')
                ->leftJoin('tc_porcentajes_utilidad AS T2', 'T2.id_tipo_configuracion', '=', 'T1.id_tipo_configuracion')
                ->select(
                    'T2.n_porcentaje_utilidad',
                )
                ->where('T1.id_tipo_configuracion', 1)
                ->first();



            if(!$utilidadBase){
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => "Error al obtener el porcetaje e utilidad base",
                ], 422);
            }

            $refaccion = new Refaccion();
            $refaccion->s_nombre_refaccion          = $request->s_nombre_refaccion;
            $refaccion->s_numero_parte              = $request->s_numero_parte;
            $refaccion->s_imagen_refaccion          = $request->s_imagen_refaccion ?? null;
            $refaccion->n_precio_compra             = $request->n_precio_compra ?? 0;
            $refaccion->n_precio_venta              = $request->n_precio_venta ?? (float) ($request->n_precio_compra ? $request->n_precio_compra * (1 + $utilidadBase->n_porcentaje_utilidad / 100) : 0);
            $refaccion->n_stock_actual              = $request->n_stock_actual ?? 0;
            $refaccion->id_marca_refaccion          = $request->id_marca_refaccion;
            $refaccion->id_unidad_medida            = $request->id_unidad_medida ?? null;
            $refaccion->id_proveedor                = $request->id_proveedor ?? null;
            $refaccion->id_clase_refaccion          = $request->id_clase_refaccion ?? null;
            $refaccion->id_categoria_refaccion      = $request->id_categoria_refaccion;
            $refaccion->id_subcategoria_refaccion   = $request->id_subcategoria_refaccion;
            $refaccion->id_posicion_vehiculo        = $request->id_posicion_vehiculo ?? null;
            $refaccion->id_ubicacion_almacen        = $request->id_ubicacion_almacen ?? null;
            $refaccion->b_importado                 = $request->b_importado ?? 0;
            $refaccion->id_usuario_crea             = $request->id_usuario_crea ?? null;
            $refaccion->id_estatus_refaccion        = 1;                                                            //TODO Entra por default en 1 o se asigna desde form?
            $refaccion->save();



            $idRefaccion = $refaccion->id_refaccion;
            $equivalentes = $request->refacciones_equivalentes ?? [];


            if (!empty($equivalentes)) {
                $activos = \DB::table('tw_refacciones')
                    ->whereIn('id_refaccion', $equivalentes)
                    ->where('b_activo', 1)
                    ->pluck('id_refaccion')
                    ->toArray();

                if (count($activos) !== count($equivalentes)) {
                    throw new \Exception("Alguna de las refacciones equivalentes no existe o está inactiva.");
                }

                $grupos = \DB::table('tr_refacciones_equivalencias')
                    ->select('id_equivalencia')
                    ->whereIn('id_refaccion', $equivalentes)
                    ->where('b_activo', 1)
                    ->distinct()
                    ->pluck('id_equivalencia')
                    ->toArray();

                if (count($grupos) > 1) {
                    throw new \Exception("Las refacciones equivalentes pertenecen a diferentes grupos activos.");
                }

                if (count($grupos) === 1) {
                    $idGrupo = $grupos[0];

                    $refEquivalencia = new RefaccionEquivalencia();
                    $refEquivalencia->id_refaccion    = $idRefaccion;
                    $refEquivalencia->id_equivalencia = $idGrupo;
                    $refEquivalencia->id_usuario_crea = $request->id_usuario_crea ?? null;
                    $refEquivalencia->save();
                } else {
                    $equivalencia = new Equivalencia();
                    $equivalencia->s_nombre_equivalencia     = 'Equivalencia ' . $equivalencia->id_equivalencia . now()->format('YmdHis');
                    $equivalencia->s_descripcion_equivalencia = 'Grupo creado automáticamente';
                    $equivalencia->id_usuario_crea           = $request->id_usuario_crea ?? null;
                    $equivalencia->save();

                    $idGrupo = $equivalencia->id_equivalencia;


                    foreach (array_merge($equivalentes, [$idRefaccion]) as $idEq) {
                        $refEquivalencia = new RefaccionEquivalencia();
                        $refEquivalencia->id_refaccion    = $idEq;
                        $refEquivalencia->id_equivalencia = $idGrupo;
                        $refEquivalencia->id_usuario_crea = $request->id_usuario_crea ?? null;
                        $refEquivalencia->save();
                    }
                }
            }


            \DB::commit();

            return response()->json([
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Refacción creada correctamente.',
                'data'    => $refaccion,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al crear la refacción.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function mostrarRefacciones()
    {
        try {
                $data = DB::table('tw_refacciones AS T1')
                    ->leftJoin('tc_marcas_refacciones AS T2', 'T1.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                    ->leftJoin('tc_unidades_medida AS T3', 'T1.id_unidad_medida', '=', 'T3.id_unidad_medida')
                    ->leftJoin('tw_proveedores AS T4', 'T1.id_proveedor', '=', 'T4.id_proveedor')
                    ->leftJoin('tc_categorias_refacciones AS T5', 'T1.id_categoria_refaccion', '=', 'T5.id_categoria_refaccion')
                    ->leftJoin('tc_subcategorias_refacciones AS T6', 'T1.id_subcategoria_refaccion', '=', 'T6.id_subcategoria_refaccion')
                    ->leftJoin('tc_posiciones_vehiculo AS T7', 'T1.id_posicion_vehiculo', '=', 'T7.id_posicion_vehiculo')
                    ->leftJoin('tc_ubicaciones_almacen AS T8', 'T1.id_ubicacion_almacen', '=', 'T8.id_ubicacion_almacen')
                    ->leftJoin('tc_estatus_refacciones AS T9', 'T1.id_estatus_refaccion', '=', 'T9.id_estatus_refaccion')
                    ->leftJoin('tc_clases_refacciones AS T10', 'T1.id_clase_refaccion', '=', 'T10.id_clase_refaccion')
                    ->select(
                        'T1.id_refaccion',
                        'T1.s_nombre_refaccion',
                        'T1.s_numero_parte',
                        'T1.n_precio_venta',
                        'T1.s_codigo_qr',
                        'T2.s_marca_refaccion',
                        'T5.s_categoria_refaccion',
                        'T6.id_subcategoria_refaccion',
                        'T6.s_subcategoria_refaccion',
                        'T1.s_imagen_refaccion',
                        'T1.n_stock_actual',
                        'T1.id_estatus_refaccion',
                        'T9.s_estatus_refaccion',
                        'T9.s_color_estatus_refaccion',
                        'T1.id_clase_refaccion',
                        'T10.s_clase_refaccion',
                        'T10.s_color_clase_refaccion',
                    )
                    ->where('T1.b_activo', 1)
                    ->orderBy('T1.id_refaccion', 'DESC')
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Operación realizada correctamente.',
                    'data' => $data,
                ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
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
            $data = DB::table('tw_refacciones AS T1')
                ->leftJoin('tc_marcas_refacciones AS T2', 'T1.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                ->leftJoin('tc_unidades_medida AS T3', 'T1.id_unidad_medida', '=', 'T3.id_unidad_medida')
                ->leftJoin('tw_proveedores AS T4', 'T1.id_proveedor', '=', 'T4.id_proveedor')
                ->leftJoin('tc_categorias_refacciones AS T5', 'T1.id_categoria_refaccion', '=', 'T5.id_categoria_refaccion')
                ->leftJoin('tc_subcategorias_refacciones AS T6', 'T1.id_subcategoria_refaccion', '=', 'T6.id_subcategoria_refaccion')
                ->leftJoin('tc_posiciones_vehiculo AS T7', 'T1.id_posicion_vehiculo', '=', 'T7.id_posicion_vehiculo')
                ->leftJoin('tc_ubicaciones_almacen AS T8', 'T1.id_ubicacion_almacen', '=', 'T8.id_ubicacion_almacen')
                ->leftJoin('tc_estatus_refacciones AS T9', 'T1.id_estatus_refaccion', '=', 'T9.id_estatus_refaccion')
                ->leftJoin('tc_clases_refacciones AS T10', 'T1.id_clase_refaccion', '=', 'T10.id_clase_refaccion')
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
                    'T1.id_marca_refaccion',
                    'T2.s_marca_refaccion',
                    'T1.id_unidad_medida',
                    'T3.s_unidad_medida',
                    'T1.id_proveedor',
                    'T4.s_proveedor',
                    'T1.id_categoria_refaccion',
                    'T5.s_categoria_refaccion',
                    'T1.id_subcategoria_refaccion',
                    'T6.s_subcategoria_refaccion',
                    'T1.id_posicion_vehiculo',
                    'T7.s_posicion_vehiculo',
                    'T1.id_ubicacion_almacen',
                    'T8.s_ubicacion_almacen',
                    'T1.id_estatus_refaccion',
                    'T9.s_estatus_refaccion',
                    'T1.b_importado',
                    'T10.id_clase_refaccion',
                    'T10.s_clase_refaccion',
                )
                ->where('id_refaccion', $id_refaccion)
                ->where('T1.b_activo', 1)
                ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se encontró una refacción activa para este ID.',
                ], 404);
            }


            $grupo_equivalencia = DB::table('tr_refacciones_equivalencias')
                ->where('id_refaccion', $id_refaccion)
                ->where('b_activo', 1)
                ->first();

            $equivalencias = [];


            if ($grupo_equivalencia) {
                $id_equivalencia = $grupo_equivalencia->id_equivalencia;


                $equivalencias = DB::table('tr_refacciones_equivalencias AS T_PIVOT')
                    ->join('tw_refacciones AS T_REF', 'T_PIVOT.id_refaccion', '=', 'T_REF.id_refaccion')
                    ->leftJoin('tc_marcas_refacciones AS T_MARCA', 'T_REF.id_marca_refaccion', '=', 'T_MARCA.id_marca_refaccion')
                    ->select(
                        'T_REF.id_refaccion',
                        'T_REF.s_nombre_refaccion',
                        'T_REF.s_numero_parte',
                        'T_MARCA.s_marca_refaccion',
                        'T_REF.s_imagen_refaccion'
                    )
                    ->where('T_PIVOT.id_equivalencia', $id_equivalencia)
                    ->where('T_PIVOT.id_refaccion', '!=', $id_refaccion)
                    ->where('T_REF.b_activo', 1)
                    ->where('T_PIVOT.b_activo', 1)
                    ->get();
            }

            $data->refacciones_equivalentes = $equivalencias;

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Operación realizada correctamente.',
                'data'    => [$data],
            ], 200);

        } catch (\Exception $e) {
            // Error general
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarRefaccion(Request $request, $id)
    {
        try {
            $request->validate([
                's_nombre_refaccion' => 'required|string|max:255',
                's_numero_parte' => 'nullable|string|max:255',
                's_imagen_refaccion' => 'nullable|string|max:255',
                'n_precio_compra' => 'nullable|numeric|min:0',
                'n_precio_venta' => 'nullable|numeric|min:0',
                'n_precio_mayoreo' => 'nullable|numeric|min:0',
                'n_stock_actual' => 'nullable|numeric|min:0',
                'id_marca_refaccion' => 'nullable|integer|exists:tc_marcas_refacciones,id_marca_refaccion',
                'id_unidad_medida' => 'nullable|integer|exists:tc_unidades_medida,id_unidad_medida',
                'id_proveedor' => 'nullable|integer|exists:tw_proveedores,id_proveedor',
                'id_clase_refaccion' => 'nullable|integer|exists:tc_clases_refacciones,id_clase_refaccion',
                'id_categoria_refaccion' => 'required|integer|exists:tc_categorias_refacciones,id_categoria_refaccion',
                'id_subcategoria_refaccion' => 'required|integer|exists:tc_subcategorias_refacciones,id_subcategoria_refaccion',
                'id_posicion_vehiculo' => 'nullable|integer|exists:tc_posiciones_vehiculo,id_posicion_vehiculo',
                'id_ubicacion_almacen' => 'nullable|integer|exists:tc_ubicaciones_almacen,id_ubicacion_almacen',
                'b_importado' => 'nullable|boolean',
                'id_usuario_edita' => 'nullable|integer|exists:users,id',
                'refacciones_equivalentes' => 'nullable|array',
                'refacciones_equivalentes.*.id_refaccion' => 'integer|exists:tw_refacciones,id_refaccion',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => [$e->errors()]], 422);
        }

        try {
            DB::beginTransaction();

            $refaccion = Refaccion::findOrFail($id);
            $idUsuario = $request->id_usuario_edita ?? null;

            $refaccion->s_nombre_refaccion              = $request->s_nombre_refaccion;
            $refaccion->s_numero_parte                  = $request->s_numero_parte;
            $refaccion->s_imagen_refaccion              = $request->s_imagen_refaccion ?? $refaccion->s_imagen_refaccion;
            $refaccion->n_precio_compra                 = $request->n_precio_compra ?? $refaccion->n_precio_compra;
            $refaccion->n_precio_venta                  = $request->n_precio_venta ?? $refaccion->n_precio_venta;
            $refaccion->n_precio_mayoreo                = $request->n_precio_mayoreo ?? $refaccion->n_precio_mayoreo;
            $refaccion->n_stock_actual                  = $request->n_stock_actual ?? $refaccion->n_stock_actual;
            $refaccion->id_marca_refaccion              = $request->id_marca_refaccion ?? $refaccion->id_marca_refaccion;
            $refaccion->id_unidad_medida                = $request->id_unidad_medida ?? $refaccion->id_unidad_medida;
            $refaccion->id_proveedor                    = $request->id_proveedor ?? $refaccion->id_proveedor;
            $refaccion->id_clase_refaccion              = $request->id_clase_refaccion ?? $refaccion->id_clase_refaccion;
            $refaccion->id_categoria_refaccion          = $request->id_categoria_refaccion ?? $refaccion->id_categoria_refaccion;
            $refaccion->id_subcategoria_refaccion       = $request->id_subcategoria_refaccion ?? $refaccion->id_subcategoria_refaccion;
            $refaccion->id_posicion_vehiculo            = $request->id_posicion_vehiculo ?? $refaccion->id_posicion_vehiculo;
            $refaccion->id_ubicacion_almacen            = $request->id_ubicacion_almacen ?? $refaccion->id_ubicacion_almacen;
            $refaccion->b_importado                     = $request->b_importado ?? $refaccion->b_importado;
            $refaccion->id_usuario_edita                = $request->id_usuario_edita  ?? null;
            $refaccion->save();


            $grupoActual = DB::table('tr_refacciones_equivalencias')
                ->where('id_refaccion', $id)->where('b_activo', 1)->first();
            $idGrupoActual = $grupoActual ? $grupoActual->id_equivalencia : null;
            $nuevosEquivalentesIds = $request->input('refacciones_equivalentes', []);

            if (empty($nuevosEquivalentesIds)) {
                if ($idGrupoActual) {
                    DB::table('tr_refacciones_equivalencias')
                        ->where('id_refaccion', $id)
                        ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);

                    $miembrosActivos = DB::table('tr_refacciones_equivalencias')
                        ->where('id_equivalencia', $idGrupoActual)->where('b_activo', 1)->count();

                    if ($miembrosActivos <= 1) {
                        DB::table('tr_refacciones_equivalencias')
                            ->where('id_equivalencia', $idGrupoActual)
                            ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);

                        DB::table('tw_equivalencias')
                            ->where('id_equivalencia', $idGrupoActual)
                            ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);
                    }
                }

            }

            else {



                $nuevosEquivalentes = collect(
                    $request->input('refacciones_equivalentes', [])
                );

                $idsRefacciones = $nuevosEquivalentes
                    ->pluck('id_refaccion')
                    ->filter()       // elimina nulls
                    ->unique()       // evita duplicados
                    ->values()
                    ->toArray();

                $gruposNuevos = DB::table('tr_refacciones_equivalencias')
                    ->whereIn('id_refaccion', $idsRefacciones)
                    ->where('b_activo', 1)
                    ->distinct()
                    ->pluck('id_equivalencia');



                if ($gruposNuevos->count() > 1) {
                    throw new \Exception("Error: Las refacciones seleccionadas pertenecen a grupos de equivalencia distintos.");
                }

                $idGrupoNuevo = $gruposNuevos->first();

                if ($idGrupoActual && $idGrupoNuevo && $idGrupoActual != $idGrupoNuevo) {
                    throw new \Exception("Error: No se puede mover una refacción de un grupo a otro en esta operación.");
                }



                $idDelGrupoFinal = $idGrupoActual ?: $idGrupoNuevo;




                if (!$idDelGrupoFinal) {
                    $nuevoGrupo = new Equivalencia();
                    $nuevoGrupo->s_nombre_equivalencia = 'Grupo para ' . $refaccion->s_nombre_refaccion;
                    $nuevoGrupo->id_usuario_crea = $idUsuario;
                    $nuevoGrupo->save();
                    $idDelGrupoFinal = $nuevoGrupo->id_equivalencia;
                }



                $todasLasRefaccionesIds = collect([$id])
                    ->merge($idsRefacciones)
                    ->unique()
                    ->values()
                    ->toArray();





                foreach ($todasLasRefaccionesIds as $refId) {
                    DB::table('tr_refacciones_equivalencias')->updateOrInsert(
                        ['id_refaccion' => $refId, 'id_equivalencia' => $idDelGrupoFinal],
                        ['b_activo' => 1, 'id_usuario_crea' => $idUsuario, 'id_usuario_edita' => $idUsuario, 'updated_at' => now()]
                    );
                }
            }


            DB::commit();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Refacción actualizada correctamente.',
                'data'    => $refaccion,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status'  => 'error', 'code'    => 404, 'message' => 'La refacción no existe.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'  => 'error', 'code'    => 500, 'message' => 'Error al actualizar la refacción.', 'error'   => $e->getMessage()], 500);
        }
    }

    public function crearRefaccionesMasivo(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'refacciones' => 'required|array|min:1',
                'refacciones.*.s_nombre_refaccion' => 'required|string|max:255',
                'refacciones.*.s_numero_parte' => 'required|string|max:255|unique:tw_refacciones,s_numero_parte',
                'refacciones.*.id_marca_refaccion' => 'required|integer|exists:tc_marcas_refacciones,id_marca_refaccion',
                'refacciones.*.id_categoria_refaccion' => 'required|integer|exists:tc_categorias_refacciones,id_categoria_refaccion',
                'refacciones.*.id_subcategoria_refaccion' => 'required|integer|exists:tc_subcategorias_refacciones,id_subcategoria_refaccion',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Error de validación.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $refaccionesCreadas = [];

        try {
            DB::beginTransaction();

            $datosRefacciones = $request->input('refacciones');

            foreach ($datosRefacciones as $datos) {
                $refaccion = new Refaccion();

                $refaccion->s_nombre_refaccion     = $datos['s_nombre_refaccion'];
                $refaccion->s_numero_parte         = $datos['s_numero_parte'];
                $refaccion->id_marca_refaccion     = $datos['id_marca_refaccion'];
                $refaccion->id_categoria_refaccion = $datos['id_categoria_refaccion'];
                $refaccion->id_subcategoria_refaccion = $datos['id_subcategoria_refaccion'];

                $refaccion->s_imagen_refaccion   = null;
                $refaccion->n_precio_compra      = 0;
                $refaccion->n_precio_venta       = 0;
                $refaccion->n_stock_actual       = 0;
                $refaccion->id_unidad_medida     = null;
                $refaccion->id_proveedor          = null;
                $refaccion->id_clase_refaccion   = null;
                $refaccion->id_posicion_vehiculo = null;
                $refaccion->id_ubicacion_almacen = null;
                $refaccion->b_importado          = 0;
                $refaccion->id_estatus_refaccion = 1;

                $refaccion->id_usuario_crea = auth()->id();
                $refaccion->b_activo = 1;

                $refaccion->save();

                $refaccionesCreadas[] = $refaccion;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => count($refaccionesCreadas) . ' refacciones han sido creadas correctamente.',
                'data' => $refaccionesCreadas,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Ocurrió un error al crear las refacciones.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
