<?php

namespace App\Http\Requests\Embarques;

use Illuminate\Foundation\Http\FormRequest;

class AprobarEmbarqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pendientes' => ['nullable', 'array'],
            'pendientes.*.s_nombre_refaccion' => ['required', 'string'],
            'pendientes.*.s_numero_parte' => ['nullable', 'string'],
            'pendientes.*.id_marca_refaccion' => ['nullable', 'integer'],
            'pendientes.*.id_categoria_refaccion' => ['nullable', 'integer'],
            'pendientes.*.id_subcategoria_refaccion' => ['nullable', 'integer'],
            'pendientes.*.id_clase_refaccion' => ['nullable', 'integer'],
            'pendientes.*.n_precio_compra' => ['required', 'numeric', 'min:0'],
            'entradas' => ['nullable', 'array'],
            'entradas.*.id_refaccion' => ['required', 'integer', 'exists:tw_refacciones,id_refaccion'],
            'entradas.*.n_cantidad' => ['required', 'numeric', 'min:0'],
            'entradas.*.n_precio_compra' => ['required', 'numeric', 'min:0'],
        ];
    }
}
