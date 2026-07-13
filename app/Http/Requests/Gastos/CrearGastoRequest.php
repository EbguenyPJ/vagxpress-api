<?php

namespace App\Http\Requests\Gastos;

use Illuminate\Foundation\Http\FormRequest;

class CrearGastoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_tipo_gasto' => ['required', 'integer', 'exists:tc_tipos_gastos,id_tipo_gasto'],
            's_concepto' => ['required', 'string'],
            'n_costo' => ['nullable', 'numeric', 'min:0'],
            'n_cantidad' => ['nullable', 'numeric', 'min:1'],
            'id_sucursal' => ['nullable', 'integer', 'exists:tw_sucursales,id_sucursal'],
            'd_fecha_gasto' => ['nullable', 'date'],
            'archivo.evidencia' => ['nullable', 'string'],
            'extension' => ['nullable', 'string'],
        ];
    }
}
