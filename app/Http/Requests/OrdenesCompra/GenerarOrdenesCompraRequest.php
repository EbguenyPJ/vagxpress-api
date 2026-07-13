<?php

namespace App\Http\Requests\OrdenesCompra;

use Illuminate\Foundation\Http\FormRequest;

class GenerarOrdenesCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ordenes' => ['required', 'array', 'min:1'],
            'ordenes.*.id_requisicion' => ['required', 'integer', 'exists:tw_requisiciones,id_requisicion'],
            'ordenes.*.id_proveedor' => ['nullable', 'integer', 'exists:tw_proveedores,id_proveedor'],
            'ordenes.*.refacciones' => ['required', 'array', 'min:1'],
            'ordenes.*.refacciones.*.id_requisicion_refaccion' => ['required', 'integer', 'exists:tr_requisiciones_refacciones,id_requisicion_refaccion'],
            'ordenes.*.refacciones.*.b_autorizada' => ['required', 'boolean'],
        ];
    }
}
