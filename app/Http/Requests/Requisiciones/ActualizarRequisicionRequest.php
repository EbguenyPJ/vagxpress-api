<?php

namespace App\Http\Requests\Requisiciones;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarRequisicionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_estatus_requisicion' => ['required', 'integer', 'exists:tc_estatus_requisiciones,id_estatus_requisicion'],
        ];
    }
}
