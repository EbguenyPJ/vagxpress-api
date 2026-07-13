<?php

namespace App\Http\Requests\Refacciones;

use Illuminate\Foundation\Http\FormRequest;

class CrearRefaccionesMasivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refacciones' => ['required', 'array', 'min:1'],
            'refacciones.*.s_nombre_refaccion' => ['required', 'string', 'max:255'],
            'refacciones.*.s_numero_parte' => ['required', 'string', 'max:255', 'unique:tw_refacciones,s_numero_parte'],
            'refacciones.*.id_marca_refaccion' => ['required', 'integer', 'exists:tc_marcas_refacciones,id_marca_refaccion'],
            'refacciones.*.id_categoria_refaccion' => ['required', 'integer', 'exists:tc_categorias_refacciones,id_categoria_refaccion'],
            'refacciones.*.id_subcategoria_refaccion' => ['required', 'integer', 'exists:tc_subcategorias_refacciones,id_subcategoria_refaccion'],
        ];
    }
}
