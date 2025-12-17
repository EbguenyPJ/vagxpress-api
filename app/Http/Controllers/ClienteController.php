<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ClienteController extends Controller
{
    public function getClientes()
    {
        try {
            $data = DB::table('tw_clientes AS T1')
                ->select(
                    'T1.id_cliente',
                    'T1.s_nombre_cliente',
                    DB::raw('(T1.n_limite_credito - T1.n_saldo_actual) as saldo_actual')
                )

                ->where('T1.b_activo', 1)
                ->where('b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay categorias disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Categorias de modulos obtenidas correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener categorias de modulos',
                'error' => $e->getMessage()
            ];
        }
    }

  
 
    public function getAll()
    {
        try {
            $data = DB::table('tw_clientes AS T1')
                ->leftJoin(
                    'tc_tipos_clientes AS T2',
                    'T1.id_tipo_cliente',
                    '=',
                    'T2.id_tipo_cliente'
                )
                ->select(
                    'T1.id_cliente',
                    'T1.s_nombre_cliente',
                    'T1.s_razon_social',
                    'T1.s_rfc',
                    'T1.s_ine',
                    'T1.s_numero_telefono',
                    'T1.s_correo',
                    'T1.s_comentario',
                    'T1.n_saldo_actual',
                    'T1.n_limite_credito',
                    'T1.id_tipo_cliente',
                    'T1.id_usuario_crea',
                    'T1.id_usuario_modifica',
                    'T1.b_credito',
                    'T1.b_activo',
                    'T2.s_tipo_cliente'
                )
                ->where('T1.b_activo', 1)
                ->orderBy('T1.id_cliente', 'ASC')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No hay clientes disponibles'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function crearCliente(Request $request)
    {
        try {
            // Validación básica
            $request->validate([
                's_nombre_cliente'   => 'required|string|max:255',
                's_razon_social'     => 'nullable|string|max:255',
                's_rfc'              => 'nullable|string|max:20',
                's_ine'              => 'nullable|string|max:50',
                's_numero_telefono'  => 'nullable|string|max:20',
                's_correo'           => 'nullable|email|max:255',
                'id_tipo_cliente'    => 'required|integer',
                'id_usuario_crea'    => 'required|integer'
            ]);

            $idCliente = DB::table('tw_clientes')->insertGetId([
                's_nombre_cliente'   => $request->s_nombre_cliente,
                's_razon_social'     => $request->s_razon_social,
                's_rfc'              => $request->s_rfc,
                's_ine'              => $request->s_ine,
                's_numero_telefono'  => $request->s_numero_telefono,
                's_correo'           => $request->s_correo,
                'id_tipo_cliente'    => $request->id_tipo_cliente,
                'id_usuario_crea'    => $request->id_usuario_crea,

                // Valores por defecto
                'n_saldo_actual'     => 0,
                'n_limite_credito'   => 0,
                'b_credito'          => 0,
                'b_activo'           => 1,
                'created_at'         => now(),
                'updated_at'         => now()
            ]);

            return [
                'status'  => 'success',
                'code'    => 201,
                'message' => 'Cliente creado correctamente',
                'data'    => [
                    'id_cliente' => $idCliente
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al crear el cliente',
                'error'   => $e->getMessage()
            ];
        }
    }


    public function actualizarCliente(Request $request, $id_cliente)
    {
        try {
            //  Validación
            $request->validate([
                's_nombre_cliente'    => 'required|string|max:255',
                's_razon_social'      => 'nullable|string|max:255',
                's_rfc'               => 'nullable|string|max:20',
                's_ine'               => 'nullable|string|max:50',
                's_numero_telefono'   => 'nullable|string|max:20',
                's_correo'            => 'nullable|email|max:255',
                'id_tipo_cliente'     => 'required|integer',
                'id_usuario_modifica' => 'required|integer'
            ]);

            // 🔹 Update
            $updated = DB::table('tw_clientes')
                ->where('id_cliente', $id_cliente)
                ->update([
                    's_nombre_cliente'    => $request->s_nombre_cliente,
                    's_razon_social'      => $request->s_razon_social,
                    's_rfc'               => $request->s_rfc,
                    's_ine'               => $request->s_ine,
                    's_numero_telefono'   => $request->s_numero_telefono,
                    's_correo'            => $request->s_correo,
                    'id_tipo_cliente'     => $request->id_tipo_cliente,
                    'id_usuario_modifica' => $request->id_usuario_modifica,
                    'updated_at'          => now()
                ]);


            if ($updated === 0) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => 404,
                    'message' => 'Cliente no encontrado o sin cambios'
                ], 404);
            }

    
            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Cliente actualizado correctamente'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 422,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al actualizar el cliente',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
