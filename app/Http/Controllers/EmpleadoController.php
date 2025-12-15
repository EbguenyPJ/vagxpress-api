<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function listarEmpleados()
    {
        try {
            $data = DB::table('tw_empleados AS T1')
                ->leftjoin('tc_tipos_empleados AS T2', 'T2.id_tipo_empleado', 'T1.id_tipo_empleado')
                ->leftjoin('tc_profesiones as T3', 'T3.id_profesion', '=', 'T1.id_profesion')
                ->leftjoin('tc_grados_estudios as T4', 'T4.id_grado_estudios', '=', 'T1.id_grado_estudios')
                ->leftjoin('tw_sucursales as T5', 'T5.id_sucursal', '=', 'T1.id_sucursal')
                ->leftjoin('tc_estados_disponibilidad as T6', 'T6.id_estado_disponibilidad', '=', 'T1.id_estado_disponibilidad')
                ->select(
                    'T1.*',
                    'T2.s_tipo_empleado',
                    'T3.s_profesion',
                    'T4.s_grado_estudios',
                    'T5.s_sucursal',
                    'T6.s_estado_disponibilidad'
                )
                ->orderBy('T1.id_empleado', 'DESC')
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay empleados registrados',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Empleados obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener lista de empleados',
                'error' => $e->getMessage()
            ];
        }
    }

    public function listarEmpleadosPorUsuario($id_usuario)/// falta el idusuario en la tabla tw_empleados
    {
        try {
            $empleado = DB::table('tw_empleados AS T1')
                ->leftjoin('tc_tipos_empleados AS T2', 'T2.id_tipo_empleado', 'T1.id_tipo_empleado')
                ->select(
                    'T1.*',
                    'T2.s_tipo_empleado'
                )
                ->where('T1.id_usuario', $id_usuario)
                ->first();

            if (!$empleado) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Empleado no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $empleado
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarEmpleadosSinUsuario()
    {
        try {
            $data = DB::table('tw_empleados AS T1')
                ->leftjoin('tc_tipos_empleados AS T2', 'T2.id_tipo_empleado', 'T1.id_tipo_empleado')
                ->leftjoin('tc_profesiones as T3', 'T3.id_profesion', '=', 'T1.id_profesion')
                ->leftjoin('tc_grados_estudios as T4', 'T4.id_grado_estudios', '=', 'T1.id_grado_estudios')
                ->leftjoin('tw_sucursales as T5', 'T5.id_sucursal', '=', 'T1.id_sucursal')
                ->leftjoin('tc_estados_disponibilidad as T6', 'T6.id_estado_disponibilidad', '=', 'T1.id_estado_disponibilidad')
                ->select(
                    'T1.*',
                    'T2.s_tipo_empleado',
                    'T3.s_profesion',
                    'T4.s_grado_estudios',
                    'T5.s_sucursal',
                    'T6.s_estado_disponibilidad'
                )
                ->where('T1.b_es_usuario', 0)
                ->orderBy('T1.id_empleado', 'DESC')
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay empleados registrados',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Empleados obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener lista de empleados',
                'error' => $e->getMessage()
            ];
        }
    }

    public function crearEmpleado(Request $request)
    {
        // Validar si el empleado ya existe
        $empleadoExistente = Empleado::where('s_nombre', $request->s_nombre)
            ->where('s_apellido_paterno', $request->s_apellido_paterno)
            ->where('s_apellido_materno', $request->s_apellido_materno)
            ->where('d_fecha_nacimiento', $request->d_fecha_nacimiento)
            ->first();

        if ($empleadoExistente) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya existe un empleado con estos datos'
            ], 409);
        }

        try {
            $empleado = new Empleado();

            // Limpiar el número de teléfono (eliminar caracteres no numéricos)
            $telefono = preg_replace('/[^0-9]/', '', $request->n_telefono);
            if (empty($telefono)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El número de teléfono no es válido'
                ], 400);
            }

            $empleado->fill([
                'id_tipo_empleado' => $request->id_tipo_empleado,
                'id_profesion' => $request->id_profesion,
                'id_grado_estudios' => $request->id_grado_estudios,
                'id_sucursal' => $request->id_sucursal,
                'id_usuario' => null,
                'id_estado_disponibilidad' => 1,
                'id_sexo' => $request->id_sexo,
                's_nombre' => $request->s_nombre,
                's_apellido_paterno' => $request->s_apellido_paterno,
                's_apellido_materno' => $request->s_apellido_materno,
                's_foto_empleado' => 'empleado-default.png',
                'n_telefono' => $telefono,
                's_correo' => $request->s_correo,
                's_direccion' => $request->s_direccion,
                'd_fecha_nacimiento' => $request->d_fecha_nacimiento,
                'd_fecha_ingreso' => $request->d_fecha_ingreso,
                'b_es_usuario' => 0,
                'b_activo' => 1
            ]);

            $empleado->save();

            // Actualizar el QR con el ID generado
            $empleado->s_qr_empleado = "ESC-" . $empleado->id_empleado;

            // Procesar imagen solo si es un base64 válido
            if (!empty($request->s_foto_empleado)) {
                $newImageName = $this->saveBase64Image($request->s_foto_empleado, 'empleado_' . $empleado->id_empleado . '_');
                if ($newImageName !== 'empleado-default.png') {
                    $empleado->s_foto_empleado = $newImageName;
                }
            }

            $empleado->save();

            // Asignar habilidades automáticamente
            $this->asignarHabilidadesPorTipo($empleado->id_empleado, $request->id_tipo_empleado);

            return response()->json([
                'status' => 'success',
                'message' => 'Empleado creado con éxito',
                'data' => $empleado
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarEmpleado(Request $request, $id_empleado)
    {
        try {
            $empleado = Empleado::find($id_empleado);

            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], 404);
            }

            // Guardar el tipo anterior para comparar
            $tipo_anterior = $empleado->id_tipo_empleado;

            // Actualizar campos básicos
            $empleado->fill($request->only([
                'id_tipo_empleado',
                'id_profesion',
                'id_grado_estudios',
                'id_sucursal',
                'id_sexo',
                's_nombre',
                's_apellido_paterno',
                's_apellido_materno',
                'n_telefono',
                'n_telefono_contacto_emergencia',
                's_correo',
                's_direccion',
                'd_fecha_nacimiento',
                'd_fecha_ingreso'
            ]));

            // Limpiar teléfonos si vienen
            if ($request->has('n_telefono')) {
                $empleado->n_telefono = preg_replace('/[^0-9]/', '', $request->n_telefono);
            }

            if ($request->has('n_telefono_contacto_emergencia')) {
                $empleado->n_telefono_contacto_emergencia = preg_replace('/[^0-9]/', '', $request->n_telefono_contacto_emergencia);
            }

            // Manejo de imagen SOLO si viene explícitamente
            if ($request->has('s_foto_empleado')) {
                if ($request->s_foto_empleado === null) {
                    // Mantener imagen actual
                } elseif (!empty($request->s_foto_empleado)) {
                    // Eliminar imagen anterior si existe
                    if ($empleado->s_foto_empleado && $empleado->s_foto_empleado != 'empleado-default.png') {
                        $this->deleteImage($empleado->s_foto_empleado);
                    }
                    $empleado->s_foto_empleado = $this->saveBase64Image($request->s_foto_empleado, 'empleado_' . $id_empleado . '_');
                }
            }

            $empleado->save();

            // Verificar si cambió el tipo de empleado
            if ($request->has('id_tipo_empleado') && $request->id_tipo_empleado != $tipo_anterior) {
                $this->actualizarHabilidadesPorTipo($id_empleado, $request->id_tipo_empleado);
            }

            return response()->json([
                'message' => 'Empleado actualizado con éxito',
                'data' => $empleado
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarFotoEmpleado(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado..!!'], 404);
        }

        if (isset($request->s_foto_empleado) && $request->s_foto_empleado) {
            // Eliminar imagen anterior si existe
            if ($empleado->s_foto_empleado && $empleado->s_foto_empleado != 'empleado-default.png') {
                $this->deleteImage($empleado->s_foto_empleado);
            }

            $empleado->s_foto_empleado = $this->saveBase64Image($request->s_foto_empleado, 'empleado_' . $id . '_');
            $empleado->save();

            return response()->json(['message' => 'Foto actualizada con éxito..!!'], 200);
        }

        return response()->json(['message' => 'No se proporcionó imagen..!!'], 400);
    }

    private function saveBase64Image($base64Image, $prefix = 'empleado_')
    {
        // Si no viene imagen o no es un base64 válido, retornar imagen por defecto
        if (empty($base64Image)) {
            return 'empleado-default.png';
        }

        // Verificar si es realmente un base64 de imagen
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            return 'empleado-default.png';
        }

        $extension = strtolower($matches[1]);
        $allowedExtensions = ['png', 'jpg', 'jpeg'];

        if (!in_array($extension, $allowedExtensions)) {
            return 'empleado-default.png';
        }

        try {
            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
            if ($imageData === false) {
                return 'empleado-default.png';
            }

            // Crear directorio si no existe
            $directory = public_path('empleados');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $imageName = rtrim($prefix, '_') . '_' . time() . '.' . $extension;
            $filePath = $directory . '/' . $imageName;

            if (file_put_contents($filePath, $imageData)) {
                return $imageName;
            }

            return 'empleado-default.png';
        } catch (\Exception $e) {
            return 'empleado-default.png';
        }
    }

    private function deleteImage($imageName)
    {
        if ($imageName && $imageName !== 'empleado-default.png') {
            $filePath = public_path('empleados/' . $imageName);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
    }

    public function obtenerHabilidadesEmpleado($id_empleado)
    {
        try {
            // Verificar si el empleado existe
            $empleado = DB::table('tw_empleados AS T0')
                ->where('T0.id_empleado', $id_empleado)
                ->first();

            if (!$empleado) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Empleado no encontrado',
                ], 404);
            }

            $habilidades = DB::table('tr_habilidades_empleados AS T1')
                ->join('tc_habilidades AS T2', 'T2.id_habilidad', '=', 'T1.id_habilidad')
                ->join('tc_tipos_empleados AS T3', 'T3.id_tipo_empleado', '=', 'T2.id_tipo_empleado')
                ->select(
                    'T1.id_habilidad_empleado',
                    'T1.id_habilidad',
                    'T1.id_empleado',
                    'T1.n_nivel_dominio',
                    'T1.b_activo',
                    'T2.s_habilidad_empleado',
                    'T3.s_tipo_empleado'
                )
                ->where('T1.id_empleado', $id_empleado)
                ->where('T1.b_activo', 1)
                ->orderBy('T2.s_habilidad_empleado', 'ASC')
                ->get();

            if ($habilidades->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El empleado no tiene habilidades registradas',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Habilidades obtenidas correctamente',
                'data' => $habilidades
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener las habilidades del empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarHabilidadesEmpleado(Request $request, $id_empleado)
    {
        try {
            // Validar que el empleado existe
            $empleado = Empleado::find($id_empleado);
            if (!$empleado) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Empleado no encontrado'
                ], 404);
            }

            // Validar que vienen habilidades
            $habilidades = $request->all();
            if (!is_array($habilidades) || empty($habilidades)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No se proporcionaron habilidades para actualizar'
                ], 400);
            }

            // Actualizar cada habilidad
            DB::beginTransaction();

            foreach ($habilidades as $habilidad) {
                // Validar estructura de cada habilidad
                if (
                    !isset($habilidad['id_habilidad_empleado']) ||
                    !isset($habilidad['id_habilidad']) ||
                    !isset($habilidad['n_nivel_dominio'])
                ) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Estructura de habilidades inválida'
                    ], 400);
                }

                // Actualizar el registro sin el updated_at field
                DB::table('tr_habilidades_empleados')
                    ->where('id_habilidad_empleado', $habilidad['id_habilidad_empleado'])
                    ->where('id_empleado', $id_empleado)
                    ->where('id_habilidad', $habilidad['id_habilidad'])
                    ->update([
                        'n_nivel_dominio' => $habilidad['n_nivel_dominio']
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Habilidades actualizadas correctamente',
                'data' => $habilidades
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al actualizar habilidades del empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function asignarHabilidadesPorTipo($id_empleado, $id_tipo_empleado)
    {
        try {
            $habilidades = DB::table('tc_habilidades')
                ->where('id_tipo_empleado', $id_tipo_empleado)
                ->where('b_activo', 1)
                ->get();

            foreach ($habilidades as $habilidad) {
                DB::table('tr_habilidades_empleados')->insert([
                    'id_habilidad' => $habilidad->id_habilidad,
                    'id_empleado' => $id_empleado,
                    'n_nivel_dominio' => 1,
                    'b_activo' => 1
                ]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function actualizarHabilidadesPorTipo($id_empleado, $id_tipo_empleado)
    {
        try {
            DB::beginTransaction();

            // Eliminar definitivamente las habilidades anteriores
            DB::table('tr_habilidades_empleados')
                ->where('id_empleado', $id_empleado)
                ->delete();

            // Asignar las nuevas habilidades
            $this->asignarHabilidadesPorTipo($id_empleado, $id_tipo_empleado);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function obtenerGerenteSucursal($id_sucursal)
    {
        try {
            $gerente = DB::table('tw_empleados AS T1')
                ->leftjoin('tc_tipos_empleados AS T2', 'T2.id_tipo_empleado', 'T1.id_tipo_empleado')
                ->select(
                    'T1.*',
                    'T2.s_tipo_empleado'
                )
                ->where('T1.id_sucursal', $id_sucursal)
                ->where('T2.s_tipo_empleado', 'like', '%gerente%')
                ->first();

            if (!$gerente) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gerente no encontrado para esta sucursal'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $gerente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener gerente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarEmpleadosPorSucursal($id_sucursal)
    {
        try {
            $empleados = DB::table('tw_empleados AS T1')
                ->leftjoin('tc_tipos_empleados AS T2', 'T2.id_tipo_empleado', 'T1.id_tipo_empleado')
                ->leftjoin('tc_profesiones as T3', 'T3.id_profesion', '=', 'T1.id_profesion')
                ->leftjoin('tc_grados_estudios as T4', 'T4.id_grado_estudios', '=', 'T1.id_grado_estudios')
                ->leftjoin('tc_estados_disponibilidad as T5', 'T5.id_estado_disponibilidad', '=', 'T1.id_estado_disponibilidad')
                ->select(
                    'T1.*',
                    'T2.s_tipo_empleado',
                    'T3.s_profesion',
                    'T4.s_grado_estudios',
                    'T5.s_estado_disponibilidad'
                )
                ->where('T1.id_sucursal', $id_sucursal)
                ->where('T1.b_activo', 1)
                ->orderBy('T2.s_tipo_empleado', 'ASC')
                ->get();

            if ($empleados->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay empleados registrados en esta sucursal',
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Empleados obtenidos correctamente',
                'data' => $empleados
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener lista de empleados',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
