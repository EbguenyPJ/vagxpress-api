<?php

namespace App\Http\Requests\Cortes;

use Illuminate\Foundation\Http\FormRequest;

class SubirEvidenciasCorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_corte' => ['required', 'integer', 'exists:tw_cortes,id_corte'],
            'evidencias' => ['required', 'array', 'min:1'],
            'evidencias.*.archivo' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'evidencias.*.id_metodo_pago' => ['required', 'integer', 'exists:tc_metodos_pagos,id_metodo_pago'],
            'evidencias.*.id_tipo_evidencia' => ['required', 'integer', 'exists:tc_tipos_evidencias,id_tipo_evidencia'],
            'evidencias.*.s_descripcion' => ['nullable', 'string'],
        ];
    }
}
