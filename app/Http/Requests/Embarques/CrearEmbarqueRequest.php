<?php

namespace App\Http\Requests\Embarques;

use Illuminate\Foundation\Http\FormRequest;

class CrearEmbarqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_proveedor' => ['required', 'integer', 'exists:tw_proveedores,id_proveedor'],
            'evidencias' => ['nullable', 'array'],
            'evidencias.*.imagen' => ['required', 'string'],
            'factura' => ['nullable', 'string'],
            'extension' => ['nullable', 'string', 'in:jpg,jpeg,png,pdf,JPG,JPEG,PNG,PDF'],
            'entradas' => ['required', 'array', 'min:1'],
            'entradas.*.id_refaccion' => ['nullable', 'integer', 'exists:tw_refacciones,id_refaccion'],
            'entradas.*.n_cantidad_recibida' => ['required', 'numeric', 'min:1'],
            'entradas.*.n_precio_compra' => ['required', 'numeric', 'min:0'],
            'entradas.*.codigo_barras' => ['nullable', 'string'],
            'entradas.*.s_nombre_refaccion' => ['required_without:entradas.*.id_refaccion', 'nullable', 'string'],
            'entradas.*.s_numero_parte' => ['nullable', 'string'],
            'entradas.*.id_marca_refaccion' => ['nullable', 'integer'],
            'entradas.*.id_categoria_refaccion' => ['nullable', 'integer'],
            'entradas.*.id_subcategoria_refaccion' => ['nullable', 'integer'],
            'entradas.*.id_clase_refaccion' => ['nullable', 'integer'],
        ];
    }
}
