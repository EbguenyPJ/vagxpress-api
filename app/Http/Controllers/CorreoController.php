<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\EnviarCorreo;

use Illuminate\Http\Request;

class CorreoController extends Controller
{
    //
    public function enviarCorreo(Request $request){
        // Validamos los datos de entrada
        try{
            // Validación de los datos
            $request->validate([
                'usuario'   => 'required|string',
                'correo'    => 'required|string',
            ], [
                'usuario.required' => 'El usuario es obligatorio.',
                'usuario.string'   => 'El usuario deben ser solo letras.',
                'correo.required'  => 'El correo es obligatorio.',
                'correo.string'    => 'El correo deben ser solo letras.',
            ]);
        }catch (ValidationException $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'validacion',
                'code' => 200,
                // El mensaje de error se obtiene de la validación
                'message' => $e->validator->errors()->first()
            ], 200);
        }

        try{
            $usuario = DB::table('users')
                ->where('email', $request->correo)
                ->where('name', $request->usuario)
                ->first();

            if ($usuario) {
                // Crear contraseña
                 $nuevaPassword = Hash::make($request->password);
                 DB::table('users')
                    ->where('id', $usuario->id)
                    ->update(['password' => $nuevaPassword]);

                Mail::to($usuario->email)
                    ->cc('savecar.webmaster@gmail.com')
                    ->send(new EnviarCorreo($request));

                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'La contraseña se ha actualizado correctamente. Por favor, revise su correo ' . $usuario->email . ' para más detalles.',
                ], 200);
                
            } else {
                return response()->json([
                    'status' => 'existencia',
                    'code' => 200,
                    'message' => 'No existe ese usuario y/o correo',
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
}
