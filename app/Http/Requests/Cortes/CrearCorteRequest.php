<?php

namespace App\Http\Requests\Cortes;

use Illuminate\Foundation\Http\FormRequest;

class CrearCorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_tipo_corte' => ['required', 'integer'],
            'fecha_corte' => ['required', 'date'],
            'monto_efectivo' => ['nullable', 'numeric', 'min:0'],
            'monto_transferencia' => ['nullable', 'numeric', 'min:0'],
            'monto_credito' => ['nullable', 'numeric', 'min:0'],
            'monto_tarjeta_debito' => ['nullable', 'numeric', 'min:0'],
            'monto_tarjeta_credito' => ['nullable', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'comentario' => ['nullable', 'string'],
        ];
    }
}
