<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarTipoUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_tipo_usuario' => ['required', 'integer', 'exists:tc_tipos_usuarios,id_tipo_usuario'],
        ];
    }
}
