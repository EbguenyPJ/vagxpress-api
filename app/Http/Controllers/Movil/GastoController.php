<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastoController extends Controller
{
    //
    public function getTiposGastos()
    {
        try {
            $tipos = DB::table('tc_tipos_gastos as t')
                ->leftJoin('tc_categorias_gastos as c', 't.id_categoria_gasto', '=', 'c.id_categoria_gasto')
                ->select(
                    't.id_tipo_gasto',
                    't.s_tipo_gasto',
                    'c.id_categoria_gasto',
                    'c.s_categoria_gasto',
                    't.b_activo'
                )
                ->where('t.b_activo', 1)
                ->orderBy('t.s_tipo_gasto')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Tipos de gastos obtenidos correctamente.',
                'data' => $tipos
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los tipos de gastos: ' . $th->getMessage()
            ], 500);
        }
    }

    public function crearGastoMovil(Request $request)
    {
        try {
            // ----------------------------------------------------
            // 0. Validaciones
            // ----------------------------------------------------
            $request->validate([
                'id_tipo_gasto' => 'required|integer',
                's_concepto' => 'required|string',
                'n_costo' => 'nullable|numeric',
                'd_fecha_gasto' => 'nullable|date',
                'id_usuario_crea' => 'nullable|integer',
            ]);

            $rutaArchivo = null;
            $idTipoEvidencia = null;

            // ----------------------------------------------------
            // 1. Insertar gasto inicial sin archivo
            // ----------------------------------------------------
            $idGasto = DB::table('tw_gastos')->insertGetId([
                'id_tipo_gasto'       => $request->id_tipo_gasto,
                'id_sucursal'         => 1,
                'n_cantidad'          => 1,
                'n_costo'             => $request->n_costo ?? 0,
                's_concepto'          => $request->s_concepto,
                'd_fecha_gasto'       => $request->d_fecha_gasto ?? now(),
                'd_fecha_creacion'    => now(),
                'id_usuario_crea'     => $request->id_usuario_crea ?? null,
                'b_activo'            => 1,
                's_evidencia'        => null,
                'id_tipo_evidencia' => null,
                'b_movil'             => 1
            ]);

            // ----------------------------------------------------
            // 2. Procesar archivo si existe
            // ----------------------------------------------------

            $file = $request->archivo['evidencia'];
            $extension = $request->extension;


            $base64Str = preg_replace('/^data:[a-zA-Z0-9\/\-\.+]+;base64,/', '', $file);
            $base64Str = str_replace(' ', '+', $base64Str);
            $archivoBinario = base64_decode($base64Str);



            // Mapear extensión a tipo de evidencia
            $tipo = null;
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $tipo = 'imagen';
            } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                $tipo = 'video';
            } elseif (in_array($extension, ['mp3', 'wav'])) {
                $tipo = 'audio';
            } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                $tipo = 'documento';
            }
            if (!$tipo) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El tipo de evidencia no está permitido'
                ], 400);
            }

            // Obtener id del tipo de evidencia desde catálogo
            $idTipoEvidencia = DB::table('tc_tipos_evidencias')
                ->where('s_tipo_evidencia', $tipo)
                ->value('id_tipo_evidencia');
            $destino = public_path('evidencias_gastos');
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            // Nuevo nombre: eg_idGasto.extension
            $nombreArchivo = 'egm_' . $idGasto . '.' . $extension;
            file_put_contents($destino . '/' . $nombreArchivo, $archivoBinario);

            // Guardar SOLO el nombre del archivo en BD
            $rutaArchivo = $nombreArchivo;
            // Actualizar gasto con la ruta y tipo de evidencia
            DB::table('tw_gastos')
                ->where('id_gasto', $idGasto)
                ->update([
                    's_evidencia' => $rutaArchivo,
                    'id_tipo_evidencia' => $idTipoEvidencia
                ]);


            return response()->json([
                'status'   => true,
                'message'  => 'Gasto creado correctamente.',
                'id_gasto' => $idGasto
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear el gasto: ' . $th->getMessage()
            ], 500);
        }
    }



    public function obtenerGastos($id_sucursal)
    {
        try {
            $gastos = DB::table('tw_gastos as T1')
                ->leftJoin('tc_tipos_gastos as T2', 'T1.id_tipo_gasto', '=', 'T2.id_tipo_gasto')
                ->leftJoin('tc_categorias_gastos as T3', 'T2.id_categoria_gasto', '=', 'T3.id_categoria_gasto')
                ->leftJoin('tw_sucursales as T4', 'T1.id_sucursal', '=', 'T4.id_sucursal')
                ->leftJoin('users as T5', 'T1.id_usuario_crea', '=', 'T5.id')
                ->select(
                    'T1.id_gasto',
                    'T1.id_tipo_gasto',
                    'T1.id_tipo_evidencia',
                    'T1.id_sucursal',
                    'T1.n_cantidad',
                    'T1.n_costo',
                    'T1.s_concepto',
                    'T1.s_evidencia',
                    'T1.d_fecha_gasto',
                    'T1.d_fecha_creacion',
                    'T1.id_usuario_crea',
                    'T1.b_activo',
                    'T1.b_movil',

                    'T4.s_sucursal',
                    'T2.s_tipo_gasto',
                    'T3.s_categoria_gasto',

                    'T5.s_nombre_completo as usuario_crea'
                )
                ->where('T1.b_activo', 1)
                ->where('T1.id_sucursal', $id_sucursal)
                ->orderByDesc('T1.d_fecha_gasto')
                ->get();

            // Agregar URL completa del archivo de evidencia
            foreach ($gastos as $gasto) {
                $gasto->url_evidencia = $gasto->s_evidencia
                    ? asset('evidencias_gastos/' . $gasto->s_evidencia)
                    : null;
            }

            return response()->json([
                'status' => true,
                'message' => 'Lista de gastos obtenida correctamente.',
                'data' => $gastos
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los gastos: ' . $th->getMessage()
            ], 500);
        }
    }



    public function crearGasto(Request $request)
    {
        try {
            // ----------------------------------------------------
            // 0. Validaciones
            // ----------------------------------------------------
            $request->validate([
                'id_tipo_gasto' => 'required|integer',
                'id_sucursal' => 'required|integer',
                's_concepto' => 'required|string',
                'n_cantidad' => 'nullable|numeric',
                'n_costo' => 'nullable|numeric',
                'd_fecha_gasto' => 'nullable|date',
                'id_usuario_crea' => 'nullable|integer',
                'archivo' => 'nullable|file|max:20480', // máx 20MB
            ]);

            $rutaArchivo = null;
            $idTipoEvidencia = null;

            // ----------------------------------------------------
            // 1. Insertar gasto inicial sin archivo
            // ----------------------------------------------------
            $idGasto = DB::table('tw_gastos')->insertGetId([
                'id_tipo_gasto'       => $request->id_tipo_gasto,
                'id_sucursal'         => $request->id_sucursal,
                'n_cantidad'          => $request->n_cantidad ?? 1,
                'n_costo'             => $request->n_costo ?? 0,
                's_concepto'          => $request->s_concepto,
                'd_fecha_gasto'       => $request->d_fecha_gasto ?? now(),
                'd_fecha_creacion'    => now(),
                'id_usuario_crea'     => $request->id_usuario_crea ?? null,
                'b_activo'            => 1,
                's_evidencias'        => null,
                'id_tipos_evidencias' => null,
                'b_movil'             => 0 // Indica que no es gasto móvil
            ]);

            // ----------------------------------------------------
            // 2. Procesar archivo si existe
            // ----------------------------------------------------
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
                $extension = strtolower($file->getClientOriginalExtension());

                // Mapear extensión a tipo de evidencia
                $tipo = null;
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $tipo = 'imagen';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                    $tipo = 'video';
                } elseif (in_array($extension, ['mp3', 'wav'])) {
                    $tipo = 'audio';
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                    $tipo = 'documento';
                }

                if (!$tipo) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'El tipo de evidencia no está permitido'
                    ], 400);
                }

                // Obtener id del tipo de evidencia desde catálogo
                $idTipoEvidencia = DB::table('tc_tipos_evidencias_servicio')
                    ->where('s_tipo_evidencia_servicio', $tipo)
                    ->value('id_tipo_evidencia_servicio');

                $destino = public_path('evidencias_gastos');
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                // Nuevo nombre: eg_idGasto.extension
                $nombreArchivo = 'eg_' . $idGasto . '.' . $extension;
                $file->move($destino, $nombreArchivo);

                // Guardar SOLO el nombre del archivo en BD
                $rutaArchivo = $nombreArchivo;

                // Actualizar gasto con la ruta y tipo de evidencia
                DB::table('tw_gastos')
                    ->where('id_gasto', $idGasto)
                    ->update([
                        's_evidencias' => $rutaArchivo,
                        'id_tipos_evidencias' => $idTipoEvidencia
                    ]);
            }

            return response()->json([
                'status'   => true,
                'message'  => 'Gasto creado correctamente.',
                'id_gasto' => $idGasto
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear el gasto: ' . $th->getMessage()
            ], 500);
        }
    }


    public function getCategoriasGastos()
    {
        try {
            $categorias = DB::table('tc_categorias_gastos')
                ->select('id_categoria_gasto', 's_categoria_gasto', 'b_activo')
                ->where('b_activo', 1)
                ->orderBy('s_categoria_gasto')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Categorías de gastos obtenidas correctamente.',
                'data' => $categorias
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener las categorías: ' . $th->getMessage()
            ], 500);
        }
    }



    public function crearTipoGasto(Request $request)
    {
        try {
            $id = DB::table('tc_tipos_gastos')->insertGetId([
                's_tipo_gasto'     => $request->s_tipo_gasto,
                'id_categoria_gasto' => $request->id_categoria_gasto,
                'b_activo'         => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Tipo de gasto creado correctamente.',
                'id_tipo_gasto' => $id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al crear el tipo de gasto: ' . $th->getMessage()
            ], 500);
        }
    }
}
