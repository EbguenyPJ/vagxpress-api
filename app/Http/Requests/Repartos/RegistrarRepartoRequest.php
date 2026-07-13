<?php

namespace App\Http\Requests\Repartos;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarRepartoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_orden' => ['required', 'integer', 'exists:tw_ordenes,id_orden'],
            'evidencias_inicio_reparto' => ['nullable', 'array'],
            'evidencias_inicio_reparto.*.imagen' => ['required', 'string'],
            'evidencias_fin_reparto' => ['nullable', 'array'],
            'evidencias_fin_reparto.*.imagen' => ['required', 'string'],
            'firma_cliente' => ['nullable', 'string'],
            's_nombre_recibe' => ['nullable', 'string'],
            'hora_inicio_reparto' => ['nullable', 'date'],
            'hora_fin_reparto' => ['nullable', 'date'],
            'hora_inicio_regreso' => ['nullable', 'date'],
            'hora_fin_regreso' => ['nullable', 'date'],
            'ubicaciones_reparto' => ['nullable', 'array'],
            'ubicaciones_reparto.*.latitud' => ['required', 'numeric'],
            'ubicaciones_reparto.*.longitud' => ['required', 'numeric'],
            'ubicaciones_reparto.*.timestamp' => ['required', 'date'],
            'ubicaciones_regreso' => ['nullable', 'array'],
            'ubicaciones_regreso.*.latitud' => ['required', 'numeric'],
            'ubicaciones_regreso.*.longitud' => ['required', 'numeric'],
            'ubicaciones_regreso.*.timestamp' => ['required', 'date'],
        ];
    }
}
