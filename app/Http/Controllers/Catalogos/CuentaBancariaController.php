<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentaBancariaController extends Controller
{
    public function getCuentasBancarias()
    {
        try {
            $data = DB::table('tc_cuentas_bancarias AS T1')
                ->leftJoin('tc_bancos AS T2', 'T2.id_banco', '=', 'T1.id_banco')
                ->select(
                    'T1.id_cuenta_bancaria',
                    'T1.s_nombre_cuenta',
                    'T1.id_metodo_pago',
                    'T1.id_banco',
                    'T2.s_img_banco'
                )
                ->where('T1.b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay cuentas bancarias disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Cuentas bancarias obtenidas correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener las cuentas bancarias',
                'error' => $e->getMessage()
            ];
        }
    }
}
