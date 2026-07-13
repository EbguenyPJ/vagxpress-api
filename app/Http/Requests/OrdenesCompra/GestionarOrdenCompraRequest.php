<?php

namespace App\Http\Requests\OrdenesCompra;

use Illuminate\Foundation\Http\FormRequest;

class GestionarOrdenCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_estatus_orden_compra' => ['required', 'integer', 'in:2,3'], // 2=Aprobada, 3=Rechazada
            'refacciones' => ['present', 'array'],
            'refacciones.*.id_requisicion_refaccion' => ['required', 'integer', 'exists:tr_requisiciones_refacciones,id_requisicion_refaccion'],
            'refacciones.*.n_cantidad_solicitada' => ['required', 'integer', 'min:0'],
        ];
    }
}
