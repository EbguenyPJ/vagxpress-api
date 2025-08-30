<?php

namespace App\Helpers;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Importamos el modelo Alerta;
use App\Models\Alerta;

// Importamos el modelo TipoAlerta;
use App\Models\TipoAlerta;

use App\Events\AlertaCreada;

use Illuminate\Support\Carbon;

class AlertaHelper
{
    public static function crearAlerta($id_tipo_alerta, $s_alerta, $s_descripcion = null)
    {
        // Validación de los datos
        if (empty($id_tipo_alerta)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'El id_tipo_alerta es obligatorio.',
            ]; 
        }

        // Validación de los datos
        if (empty($s_alerta)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'El s_alerta es obligatorio.',
            ];  
        }



        try{
            $fechaActual = Carbon::now('America/Mexico_City')->toDateString();  // YYYY-MM-DD
            $horaActual = Carbon::now('America/Mexico_City')->toTimeString();   // HH:MM:SS

            $alerta = new Alerta();
            $alerta->id_tipo_alerta                = $id_tipo_alerta;
            $alerta->s_alerta                      = $s_alerta;
            $alerta->s_descripcion                 = $s_descripcion ?? null;
            $alerta->d_fecha_registro              = $fechaActual;
            $alerta->t_hora_registro               = $horaActual;
            $alerta->b_activo                      = 1;
            // Guardar la alerta en la base de datos 
            $alerta->save();

            // Buscar el tipo de alerta
            $tipoAlerta = TipoAlerta::find($alerta->id_tipo_alerta);
            $duracionHoras = $tipoAlerta ? $tipoAlerta->n_duracion_alerta : 1; // Por defecto 1 hora si no se encuentra

            // Calcular hora de cierre sumando la duración a la hora de registro
            $horaRegistroCarbon = Carbon::parse($alerta->t_hora_registro);
            $horaCierre = $horaRegistroCarbon->addHours($duracionHoras)->toTimeString(); // HH:MM:SS

            $alerta->t_hora_cierre = $horaCierre;
            $alerta->save();

        

            // Guardar la alerta en Cache (opcionalmente con TTL de unos segundos)
            Cache::put('alerta_nueva', $alerta, now()->addSeconds(10)); 

            // Respuesta de exito
            return [
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Alerta creada correctamente',
                'data'    => $alerta
            ];

        }catch (QueryException $e) {
            // Respuesta de error
            return [
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al guardar la alerta.',
                'error'   => $e->getMessage(),
            ];
        }
    }
}
