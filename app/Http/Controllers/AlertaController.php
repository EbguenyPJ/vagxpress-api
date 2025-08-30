<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\TipoAlerta;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    //
    public function getAll()
    {
        try {
            // obtenemos todos los campos
            $alertas = DB::table('tw_alertas as T1')
                ->leftjoin('tc_tipos_alertas as T2', 'T2.id_tipo_alerta', '=', 'T1.id_tipo_alerta')
                ->where('T1.b_activo', 1)
                ->orderby('T1.id_alerta', 'desc')
                ->select(
                    'T1.id_alerta',
                    'T1.s_alerta',
                    'T1.s_descripcion',
                    'T1.d_fecha_registro',
                    'T1.t_hora_registro',
                    'T1.b_activo',
                    'T2.id_tipo_alerta',
                    'T2.s_tipo_alerta',
                    'T2.n_duracion_alerta',
                    'T2.s_icono',
                    'T2.s_color',
                    'T1.b_recordatorio_cierre',
                    'T1.t_hora_cierre',
                    'T1.id_usuario',
                )
                ->get()
                ->map(function ($item) {
                    // Combina fecha y hora en un solo Carbon
                    $fechaHoraRegistro = Carbon::parse($item->d_fecha_registro . ' ' . $item->t_hora_registro);
                    $horaActual = Carbon::now('America/Mexico_City');
                    $minutosTranscurridos = $fechaHoraRegistro->diffInMinutes($horaActual);

                    return (object) [
                        // IDs y numéricos como enteros
                        'id_alerta'         => (int) $item->id_alerta,
                        's_alerta'          => (string) $item->s_alerta,
                        's_descripcion'     => (string) $item->s_descripcion,
                        'd_fecha_registro'  => (string) $item->d_fecha_registro,
                        't_hora_registro'   => (string) $item->t_hora_registro,
                        'b_activo'          => (int) $item->b_activo,
                        'id_tipo_alerta'    => (int) $item->id_tipo_alerta,
                        's_tipo_alerta'     => (string) $item->s_tipo_alerta,
                        'n_duracion_alerta' => (int) $item->n_duracion_alerta,
                        's_icono'           => (string) $item->s_icono,
                        's_color'           => (string) $item->s_color,
                        'b_recordatorio_cierre' => (int) $item->b_recordatorio_cierre,
                        't_hora_cierre'     => (string) $item->t_hora_cierre,
                        'minutos_transcurridos' => $minutosTranscurridos,
                        'hora_actual' => Carbon::now('America/Mexico_City')->toTimeString(),
                        'id_usuario' => (int) $item->id_usuario
                    ];
                });

            // verificamos si tiene datos            
            if ($alertas->isEmpty()) {
                // // Respuesta de vacio                
                return response()->json([
                    'status' => 'success',
                    'code'   => 200,
                    'message' => 'No hay alertas registradas',
                ], 200);
            } else {
                // Respuesta de exito                
                return response()->json([
                    'status'    => 'success',
                    'code'      => 200,
                    'message' => 'Alertas obtenidas correctamente',
                    'data'      => $alertas
                ], 200);
            }
        } catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Obtener todas las alertas creadas por el usuario
    public function getAlertasComponente()
    {
        try {
            $alertas = DB::table('tw_alertas as T1')
                ->leftjoin('tc_tipos_alertas as T2', 'T2.id_tipo_alerta', '=', 'T1.id_tipo_alerta')
                ->where('T1.b_activo', 1)
                ->whereBetween('T1.id_tipo_alerta', [4, 7]) // Filtro clave para tipos 4-7
                ->orderby('T1.id_alerta', 'desc')
                ->select(
                    'T1.id_alerta',
                    'T1.s_alerta',
                    'T1.s_descripcion',
                    'T1.d_fecha_registro',
                    'T1.t_hora_registro',
                    'T1.b_activo',
                    'T2.id_tipo_alerta',
                    'T2.s_tipo_alerta',
                    'T2.n_duracion_alerta',
                    'T2.s_icono',
                    'T2.s_color',
                )
                ->get()
                ->map(function ($item) {
                    $fechaHoraRegistro = Carbon::parse($item->d_fecha_registro . ' ' . $item->t_hora_registro);
                    $minutosTranscurridos = $fechaHoraRegistro->diffInMinutes(now());

                    return (object) [
                        'id_alerta'         => (int) $item->id_alerta,
                        's_alerta'          => (string) $item->s_alerta,
                        's_descripcion'     => (string) $item->s_descripcion,
                        'd_fecha_registro'  => (string) $item->d_fecha_registro,
                        't_hora_registro'   => (string) $item->t_hora_registro,
                        'b_activo'          => (int) $item->b_activo,
                        'id_tipo_alerta'    => (int) $item->id_tipo_alerta,
                        's_tipo_alerta'     => (string) $item->s_tipo_alerta,
                        'n_duracion_alerta' => (int) $item->n_duracion_alerta,
                        's_icono'           => (string) $item->s_icono,
                        's_color'           => (string) $item->s_color,
                        'minutos_transcurridos' => $minutosTranscurridos,
                    ];
                });

            if ($alertas->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'code'   => 200,
                    'message' => 'No hay alertas de componente registradas',
                ], 200);
            }

            return response()->json([
                'status'    => 'success',
                'code'      => 200,
                'message' => 'Alertas de componente obtenidas correctamente',
                'data'      => $alertas
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Obtener tipos de alerta disponibles para usuarios (solo ids 4-7)
    public function getTiposAlertasDisponibles()
    {
        try {
            $tipos = TipoAlerta::where('b_activo', 1)
                ->whereBetween('id_tipo_alerta', [4, 7])
                ->get(['id_tipo_alerta', 's_tipo_alerta', 's_icono', 's_color']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $tipos
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Crear una nueva alerta
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_tipo_alerta' => 'required|integer|between:4,7',
                's_alerta' => 'required|string|max:255',
                's_descripcion' => 'required|string',
            ]);

            $now = now();

            $alerta = Alerta::create([
                'id_tipo_alerta' => $request->id_tipo_alerta,
                's_alerta' => $request->s_alerta,
                's_descripcion' => $request->s_descripcion,
                'd_fecha_registro' => $now->toDateString(),
                't_hora_registro' => $now->toTimeString(),
                'b_activo' => 1
            ]);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Alerta creada correctamente',
                'data' => $alerta
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Actualizar una alerta
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_tipo_alerta' => 'sometimes|integer|between:4,7',
                's_alerta' => 'sometimes|string|max:255',
                's_descripcion' => 'sometimes|string',
                'b_activo' => 'sometimes|boolean'
            ]);

            $alerta = Alerta::findOrFail($id);

            $now = now();

            $data = $request->all();
            $data['d_fecha_registro'] = $now->toDateString();
            $data['t_hora_registro'] = $now->toTimeString();

            $alerta->update($data);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Alerta actualizada correctamente',
                'data' => $alerta
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Alerta no encontrada'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar (desactivar) una alerta
    public function destroy($id)
    {
        try {
            $alerta = Alerta::findOrFail($id);

            // Marcamos como inactivo en lugar de eliminar
            $alerta->update(['b_activo' => 0]);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Alerta desactivada correctamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Alerta no encontrada'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
