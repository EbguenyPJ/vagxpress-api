<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


// Importamos el modelo Version
use App\Models\Version;

use App\Helpers\AlertaHelper;

class VersionController extends Controller
{
    //
    public function getAll(){
        try{
            // obtenemos todos los campos
            $versiones = DB::table('tw_versiones as T1')
            ->leftjoin('users as T2', 'T2.id', '=', 'T1.id_usuario')
            ->leftjoin('tw_empleados as T3', 'T3.id_empleado', '=', 'T2.id_empleado')
            ->select(
                'T1.id_usuario', 'T3.s_nombre as s_nombre_usuario', 'T3.s_apellido_paterno as s_apellido_paterno_usuario', 'T3.s_apellido_materno as s_apellido_materno_usuario', 
                'T1.id_version', 'T1.s_nombre_version', 'T1.s_descripcion_version', 'T1.d_fecha_actualizacion_version', 'T1.b_activo')
            ->get()
            ->map(function ($item) {
                return (object) [
                    // IDs y numéricos como enteros
                    'id_usuario'                    => (int) $item->id_usuario,
                    's_nombre_usuario'              => (string) $item->s_nombre_usuario,
                    's_apellido_paterno_usuario'    => (string) $item->s_apellido_paterno_usuario,
                    's_apellido_materno_usuario'    => (string) $item->s_apellido_materno_usuario,
                    'id_version'                    => (int) $item->id_version,
                    's_nombre_version'              => (string) $item->s_nombre_version,
                    's_descripcion_version'         => (string) $item->s_descripcion_version,
                    'd_fecha_actualizacion_version' => (string) $item->d_fecha_actualizacion_version,
                    'b_activo'                      => (int) $item->b_activo,
                ];
            });

            // verificamos si tiene datos
            if($versiones->isEmpty()){
                // Respuesta de vacio
                return response()->json([
                    'status'    => 'success',
                    'code'      => 200,
                    'message'   => 'No hay versiones',
                ], 200); 
            }
            else{
                // Respuesta de exito
                return response()->json([
                    'status'    => 'success',
                    'code'      => 200,
                    'message'   => 'Versiones obtenidas correctamente',
                    'data'      => $versiones
                ], 200);
            }
        }catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function crearVersion(Request $request){
        // Validamos los datos de entrada
        try{
            // Validación de los datos
            $request->validate([
                'id_usuario'                    => 'required|integer',
                's_nombre_version'              => 'required|string',
                //'s_descripcion_version'         => 'required|string',
                'd_fecha_actualizacion_version' => 'required|date_format:Y-m-d',
            ], [
                'id_usuario.required'                           => 'El id_usuario es obligatorio.',
                'id_usuario.integer'                            => 'El id_usuario debe ser un número entero.',
                's_nombre_version.required'                     => 'El s_nombre_version es obligatorio.',
                's_nombre_version.string'                       => 'El s_nombre_version debe ser solo letras',
                //'s_descripcion_version.required'                => 'El s_descripcion_version es obligatorio.',
                //'s_descripcion_version.integer'                 => 'El s_descripcion_version debe ser solo letras',     
                'd_fecha_actualizacion_version.required'        => 'La d_fecha_actualizacion_version es obligatoria.',
                'd_fecha_actualizacion_version.date_format'     => 'La d_fecha_actualizacion_version debe estar en formato YYYY-MM-DD.',
            ]);
        }catch (ValidationException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                // El mensaje de error se obtiene de la validación
                'message' => $e->validator->errors()->first()
            ], 500);
        }


        try{
            return DB::transaction(function () use ($request) {
                // Crear la cita en la base de datos
                $version = new Version();
                $version->id_usuario                    = $request->id_usuario;
                $version->s_nombre_version              = $request->s_nombre_version;
                $version->s_descripcion_version         = $request->s_descripcion_version;
                $version->d_fecha_actualizacion_version = $request->d_fecha_actualizacion_version;
                $version->b_activo                      = 1;
                // Guardar la cita en la base de datos 
                $version->save();


                // llamar al servicio del helper
                $alerta = AlertaHelper::crearAlerta(
                    5, // id_tipo_alerta
                    $request->s_nombre_version, // s_alerta
                    $request->s_descripcion_version // s_descripcion (opcional)
                );

                if ($alerta['status'] === 'success') {
                    // Continuar el flujo normal
                    // Respuesta de exito
                    return response()->json([
                        'status'    => 'success',
                        'code'      => 200,
                        'message'   => 'Versión creada correctamente',
                        'data'      => $version
                    ], 200);
                }
                else{
                    // Manejar el error de creación de alerta
                    return response()->json(['message' => 'Error al crear alerta: ' . $alerta['error']], 500);
                }

            });

        }catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUltimaVersion(){
        try{
            // obtenemos todos los campos
            $versiones = DB::table('tw_versiones as T1')
            ->leftjoin('users as T2', 'T2.id', '=', 'T1.id_usuario')
            ->leftjoin('tw_empleados as T3', 'T3.id_empleado', '=', 'T2.id_empleado')
            ->orderByDesc('id_version')
            ->limit(1)
            ->select(
                'T1.id_usuario', 'T3.s_nombre as s_nombre_usuario', 'T3.s_apellido_paterno as s_apellido_paterno_usuario', 'T3.s_apellido_materno as s_apellido_materno_usuario', 
                'T1.id_version', 'T1.s_nombre_version', 'T1.s_descripcion_version', 'T1.d_fecha_actualizacion_version', 'T1.b_activo')
            ->get()
            ->map(function ($item) {
                return (object) [
                    // IDs y numéricos como enteros
                    'id_usuario'                    => (int) $item->id_usuario,
                    's_nombre_usuario'              => (string) $item->s_nombre_usuario,
                    's_apellido_paterno_usuario'    => (string) $item->s_apellido_paterno_usuario,
                    's_apellido_materno_usuario'    => (string) $item->s_apellido_materno_usuario,
                    'id_version'                    => (int) $item->id_version,
                    's_nombre_version'              => (string) $item->s_nombre_version,
                    's_descripcion_version'         => (string) $item->s_descripcion_version,
                    'd_fecha_actualizacion_version' => (string) $item->d_fecha_actualizacion_version,
                    'b_activo'                      => (int) $item->b_activo,
                ];
            });

            // verificamos si tiene datos
            if($versiones->isEmpty()){
                // Respuesta de vacio
                return response()->json([
                    'status'    => 'success',
                    'code'      => 200,
                    'message'   => 'No hay versiones',
                ], 200); 
            }
            else{
                // Respuesta de exito
                return response()->json([
                    'status'    => 'success',
                    'code'      => 200,
                    'message'   => 'Version obtenida correctamente',
                    'data'      => $versiones
                ], 200);
            }
        }catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarVersion(Request $request, $id_version){
        // Validamos los datos de entrada
        try{
            // Validación de los datos
            $request->validate([
                'id_usuario'                    => 'required|integer',
                's_nombre_version'              => 'required|string',
                //'s_descripcion_version'         => 'required|string',
                'd_fecha_actualizacion_version' => 'required|date_format:Y-m-d',
            ], [
                'id_usuario.required'                           => 'El id_usuario es obligatorio.',
                'id_usuario.integer'                            => 'El id_usuario debe ser un número entero.',
                's_nombre_version.required'                     => 'El s_nombre_version es obligatorio.',
                's_nombre_version.string'                       => 'El s_nombre_version debe ser solo letras',
                //'s_descripcion_version.required'                => 'El s_descripcion_version es obligatorio.',
                //'s_descripcion_version.integer'                 => 'El s_descripcion_version debe ser solo letras',     
                'd_fecha_actualizacion_version.required'        => 'La d_fecha_actualizacion_version es obligatoria.',
                'd_fecha_actualizacion_version.date_format'     => 'La d_fecha_actualizacion_version debe estar en formato YYYY-MM-DD.',
            ]);
        }catch (ValidationException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                // El mensaje de error se obtiene de la validación
                'message' => $e->validator->errors()->first()
            ], 500);
        }


        try{
            return DB::transaction(function () use ($request, $id_version) {
                // Buscar la version por su ID
                $version = Version::find($id_version);
                // Verificamos si la version existe
                if (!$version) {
                    // Respuesta de error
                    return response()->json([
                        'status' => 'error',
                        'code'   => 400,
                        'message' => 'No existe la versión con el id: '.$id_version,
                    ], 400); 
                }

                $version->id_usuario                    = $request->id_usuario;
                $version->s_nombre_version              = $request->s_nombre_version;
                $version->s_descripcion_version         = $request->s_descripcion_version;
                $version->d_fecha_actualizacion_version = $request->d_fecha_actualizacion_version;
                $version->b_activo                      = 1;
                // Guardar la cita en la base de datos 
                $version->save();

                // llamar al servicio del helper
                $alerta = AlertaHelper::crearAlerta(
                    5, // id_tipo_alerta
                    $request->s_nombre_version, // s_alerta
                    $request->s_descripcion_version // s_descripcion (opcional)
                );

                if ($alerta['status'] === 'success') {
                    // Continuar el flujo normal
                    // Respuesta de exito
                    return response()->json([
                        'status'    => 'success',
                        'code'      => 200,
                        'message'   => 'Versión actualizada correctamente',
                        'data'      => $version
                    ], 200);
                }
                else{
                    // Manejar el error de creación de alerta
                    return response()->json(['message' => 'Error al crear alerta: ' . $alerta['error']], 500);
                }
                
            });

        }catch (QueryException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
