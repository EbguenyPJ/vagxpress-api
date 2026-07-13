<?php

namespace App\Http\Requests\Compatibilidad;

use Illuminate\Foundation\Http\FormRequest;

class GuardarReglaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_refaccion' => ['required', 'integer', 'exists:tw_refacciones,id_refaccion'],
            'id_marcas' => ['nullable', 'array'],
            'id_marcas.*' => ['integer', 'exists:tc_marcas_vehiculos,id_marca_vehiculo'],
            'id_modelos' => ['nullable', 'array'],
            'id_modelos.*' => ['integer', 'exists:tc_modelos_vehiculos,id_modelo_vehiculo'],
            'id_generaciones' => ['nullable', 'array'],
            'id_generaciones.*' => ['integer', 'exists:tc_generaciones,id_generacion'],
            'id_motores' => ['nullable', 'array'],
            'id_motores.*' => ['integer', 'exists:tc_motores,id_motor'],
            's_resumen' => ['nullable', 'string', 'max:255'],
        ];
    }
}
