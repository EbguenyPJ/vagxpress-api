<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_empleado' => ['required', 'integer', 'exists:tw_empleados,id_empleado'],
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6'],
            'id_tipo_usuario' => ['required', 'integer', 'exists:tc_tipos_usuarios,id_tipo_usuario'],
        ];
    }
}
