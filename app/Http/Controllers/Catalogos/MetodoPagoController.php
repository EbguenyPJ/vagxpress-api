<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetodoPagoController extends Controller
{
    public function getMetodosPagos()
    {
        try {
            $data = DB::table('tc_metodos_pagos AS T1')
                ->select(
                    'T1.id_metodo_pago',
                    'T1.s_metodo_pago',
                    'T1.s_img_metodo_pago',
                )
                ->where('b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay métodos de pago disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Métodos de pagos obtenidos correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener los Métodos de pagos',
                'error' => $e->getMessage()
            ];
        }
    }
}
