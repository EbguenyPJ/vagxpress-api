<?php

namespace App\Http\Requests\Cotizaciones;

use Illuminate\Foundation\Http\FormRequest;

class CrearCotizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente' => ['required', 'integer', 'exists:tw_clientes,id_cliente'],
            'id_tipo_cotizacion' => ['nullable', 'integer', 'exists:tc_tipos_cotizaciones,id_tipo_cotizacion'],
            'refacciones' => ['required', 'array', 'min:1'],
            'refacciones.*.id_refaccion' => ['required', 'integer', 'exists:tw_refacciones,id_refaccion'],
            'refacciones.*.n_cantidad' => ['required', 'numeric', 'min:1'],
            'refacciones.*.id_porcentaje_utilidad' => ['nullable', 'integer', 'exists:tc_porcentajes_utilidad,id_porcentaje_utilidad'],
        ];
    }
}
