<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Empleado;
use App\Models\TipoEmpleado;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{


    public function getUsuarios()
    {
        try {
            $data = DB::table('users AS T1')
                ->select(
                    'T1.*',
                )
                ->orderBy('T1.id', 'ASC')
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay usuarios usuario registrados',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener lista de usuarios',
                'error' => $e->getMessage()
            ];
        }
    }


    public function registrarUsuario(Request $requets)
    {
        $requets->validate([
            'id_empleado' => 'required',
            'password' => 'required|string',
            'name' => 'required|string',
        ]);

        $empleado = Empleado::where('id_empleado', $requets->id_empleado)->first();

        if (isset($empleado->id_empleado)) {
            $user = User::where('id_empleado', $empleado->id_empleado)->first();
            if (isset($user->id)) {
                $result = [
                    'status' => 'error',
                    'code' => '204',
                    'message' => 'El empleado ya tiene un usuario registrado',
                ];

                return $result;
            } else {

                $user = new User([
                    'name' => $requets->name,
                    'password' => Hash::make($requets->password),
                    'email' => $empleado->s_correo,
                    'id_empleado' => $empleado->id_empleado,
                    's_nombre_completo' => $empleado->s_nombre . ' ' . $empleado->s_apellido_paterno . ' ' . $empleado->s_apellido_materno,
                    'b_activo' => 1,
                    's_token' => '',
                    'id_tipo_usuario' => $requets->id_tipo_usuario,

                ]);
                $user->save();

                $empleado->b_es_usuario = 1;
                $empleado->update();

                $result['data'] =
                    [
                        'status' => 'success',
                        'code' => '201',
                        'message' => '¡Usuario registrado exitosamente!',
                    ];

                return $result;
            }
        }
    }



    public function perfilUsuario($id_usuario)
    {
        //$usuario = \App\User::find($id_usuario);
        $usuario = DB::table('users as T1')
            ->join('tw_empleados as T2', 'T1.id_empleado', '=', 'T2.id_empleado')
            ->join('tc_tipos_empleados as T3', 'T2.id_tipo_empleado', '=', 'T3.id_tipo_empleado')
            //            ->join('tc_descripciones_tipos_empleados as T8', 'T3.id_tipo_empleado', '=', 'T8.id_tipo_empleado')
            //            ->join('tc_profesiones as T4', 'T2.id_profesion', '=', 'T4.id_profesion')
            //            ->join('tc_grados_estudios as T5', 'T2.id_grado_estudios', '=', 'T5.id_grado_estudios')
            //            ->join('tw_sucursales as T6', 'T2.id_sucursal', '=', 'T6.id_sucursal')
            //            ->join('tc_estados_disponibilidad as T7', 'T2.id_estado_disponibilidad', '=', 'T7.id_estado_disponibilidad')
            ->select(
                'T1.*',
                'T1.b_usuario_web',
                'T1.b_usuario_movil',
                'T2.s_nombre',
                'T2.s_apellido_paterno',
                'T2.s_apellido_materno',
                'T2.s_foto_empleado',
                'T2.s_rfc',
                'T2.s_curp',
                'T2.s_telefono',
                'T2.s_correo',
                'T2.s_direccion',
                'T2.s_num_licencia',
                'T2.s_num_seguro',
                'T2.s_qr_empleado',
                'T2.d_fecha_nacimiento',
                'T2.d_fecha_ingreso',
                'T2.s_comodin',
                'T2.id_sexo',
                'T2.s_contacto_emergencia',
                'T2.s_telefono_contacto_emergencia',
                'T3.s_tipo_empleado',
                //                'T8.s_descripcion as s_descripcion_tipo_empleado',
                //                'T4.s_profesion',
                //                'T5.s_grado_estudios',
                //                'T6.s_sucursal',
                //                'T7.s_estado_disponibilidad'
            )
            ->where('T1.id', $id_usuario)
            ->get();

        if ($usuario->isNotEmpty()) {
            return response()->json($usuario, 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }



    public function login(Request $requets)
    {
        $requets->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $requets->name)
            ->where('b_activo', '=', 1)
            ->first();

        if (!$user) {
            $result = [
                'status' => 'error',
                'code' => 401,
                'message' => "Usuario no registrado o inactivo"
            ];
            return $result;
        }

        if (isset($user->id)) {


            // Verificar si es usuario web y está activo
            if ($user->b_usuario_web == 0) {
                $result = [
                    'status' => 'error',
                    'code' => 401,
                    'message' => "No es un usuario web"
                ];

                return $result;
            }

            //obtener el empleado
            $empleado = Empleado::where('id_empleado', $user->id_empleado)->first();

            // Obtener el tipo de empleado por separado
            $tipoEmpleado = TipoEmpleado::where('id_tipo_empleado', $empleado->id_tipo_empleado)
                ->value('s_tipo_empleado');

            // Asignar el valor al objeto empleado para mantener la estructura
            $empleado->s_tipo_empleado = $tipoEmpleado ?? 'No especificado';


            if (Hash::check($requets->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $result = [
                    'status' => 'success',
                    'code' => 201,
                    'message' => '¡Datos correctos, bienvenido a TallerUp!',
                    'token' => $token,
                    'id_usuario' => $user->id,
                    'username' => $requets->name,
                    's_nombre_completo' => $user->s_nombre_completo,
                    'id_empleado' => $user->id_empleado,
                    's_foto_empleado' => $empleado->s_foto_empleado,
                    'id_tipo_usuario' => $user->id_tipo_usuario,
                    'id_tipo_empleado' => $empleado->id_tipo_empleado,
                    's_tipo_empleado' => $empleado->s_tipo_empleado,
                    'id_sucursal' => $empleado->id_sucursal,
                ];

                return $result;
            } else {

                $result = [
                    'status' => 'error',
                    'code' => 401,
                    'message' => "Contraseña incorrecta"
                ];

                return $result;
            }
        } else {
            $result = [
                'status' => 'error',
                'code' => 401,
                'message' => "Usuario no registrado"
            ];
        }
    }


    public function actualizarModuloWebMovil(Request $request, $id_usuario)
    {
        try {
            $user = User::find($id_usuario);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Usuario no encontrado',
                ], 404);
            }

            // Validar que los valores sean 0 o 1
            $request->validate([
                'b_usuario_web' => 'nullable|in:0,1',
                'b_usuario_movil' => 'nullable|in:0,1',
            ]);

            // Actualizar solo los campos que se envían
            if ($request->has('b_usuario_web')) {
                $user->b_usuario_web = $request->b_usuario_web;
            }

            if ($request->has('b_usuario_movil')) {
                $user->b_usuario_movil = $request->b_usuario_movil;
            }

            $user->save();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Modulos actualizados correctamente',
                'data' => [
                    'b_usuario_web' => (int) $user->b_usuario_web,
                    'b_usuario_movil' => (int) $user->b_usuario_movil,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al actualizar modulos del usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function actualizarEstatusUsuario(Request $request, $id_usuario)
{
    try {
        $user = User::find($id_usuario);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        // Validar que el valor sea 0 o 1
        $request->validate([
            'b_activo' => 'required|in:0,1',
        ]);

        // Actualizar el estado del usuario
        $user->b_activo = $request->b_activo;
        $user->save();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Estado del usuario actualizado correctamente',
            'data' => [
                'b_activo' => (int) $user->b_activo,
            ],
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'code' => 500,
            'message' => 'Error al actualizar el estado del usuario',
            'error' => $e->getMessage(),
        ], 500);
    }

}




}
