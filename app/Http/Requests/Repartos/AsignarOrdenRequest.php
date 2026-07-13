<?php

namespace App\Http\Requests\Repartos;

use Illuminate\Foundation\Http\FormRequest;

class AsignarOrdenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_orden' => ['required', 'integer', 'exists:tw_ordenes,id_orden'],
            'id_repartidor' => ['required', 'integer', 'exists:tw_empleados,id_empleado'],
        ];
    }
}
