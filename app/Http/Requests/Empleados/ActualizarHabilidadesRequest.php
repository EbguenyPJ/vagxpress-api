<?php

namespace App\Http\Requests\Empleados;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarHabilidadesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'habilidades' => ['required', 'array', 'min:1'],
            'habilidades.*.id_habilidad_empleado' => ['required', 'integer'],
            'habilidades.*.id_habilidad' => ['required', 'integer'],
            'habilidades.*.n_nivel_dominio' => ['required', 'integer', 'between:1,5'],
        ];
    }
}
