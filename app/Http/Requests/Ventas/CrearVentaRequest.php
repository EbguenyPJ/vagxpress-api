<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class CrearVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente' => ['required', 'integer', 'exists:tw_clientes,id_cliente'],
            'id_metodo_pago' => ['required', 'integer', 'exists:tc_metodos_pagos,id_metodo_pago'],
            'id_cuenta_bancaria' => ['nullable', 'integer', 'exists:tc_cuentas_bancarias,id_cuenta_bancaria'],
            'refacciones' => ['required', 'array', 'min:1'],
            'refacciones.*.id_refaccion' => ['required', 'integer', 'exists:tw_refacciones,id_refaccion'],
            'refacciones.*.n_cantidad' => ['required', 'numeric', 'min:1'],
            'refacciones.*.id_porcentaje_utilidad' => ['nullable', 'integer', 'exists:tc_porcentajes_utilidad,id_porcentaje_utilidad'],
        ];
    }
}
