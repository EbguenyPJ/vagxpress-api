<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Empleado;
use App\Models\TipoEmpleado;
use Illuminate\Support\Facades\Hash;

class UserMovilController extends Controller
{
    //
    public function login(Request $requets)
    {
        $requets->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $requets->name)
                    ->where('b_activo', '=', 1)
                    ->first();

        // Verificar si el usuario está activo
        if(!$user)
        {
            $result = [
                'status' => 'error',
                'code' => 401,
                'message' => "Usuario no registrado o inactivo"
            ];
            return $result;
        }

        if( isset($user->id))
        {

            if($user->b_usuario_movil == 0)
            {
                $result = [
                    'status' => 'error',
                    'code' => 401,
                    'message' => "No es un usuario móvil"
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


            if(Hash::check($requets->password, $user->password))
            {
                $token = $user->createToken('auth_token')->plainTextToken;
                $result = [
                    'status' => 'success',
                    'code' => 201,
                    'message' => '¡Datos correctos, bienvenido a TallerUp Móvil!',
                    'token' => $token,
                    'id_usuario' => $user->id,
                    'username' => $requets->name,
                    's_nombre_completo' => $user->s_nombre_completo,
                    'id_empleado' => $user->id_empleado,
                    's_foto_empleado' => $empleado->s_foto_empleado,
                    'id_tipo_usuario' => $user->id_tipo_usuario,
                    'id_tipo_empleado' => $empleado->id_tipo_empleado,
                    's_tipo_empleado' => $empleado->s_tipo_empleado,
                ];

                return $result;
            }else{

                $result = [
                    'status' => 'error',
                    'code' => 401,
                    'message' => "Contraseña incorrecta"
                ];

                return $result;
            }
        }else{
            $result = [
                'status' => 'error',
                "code" => 404,
                "message" => "Usuario no registrado"
            ];
            
            return $result;
        }
    }
}
