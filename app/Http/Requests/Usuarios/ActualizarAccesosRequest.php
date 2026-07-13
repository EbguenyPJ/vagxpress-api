<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarAccesosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'b_usuario_web' => ['sometimes', 'boolean'],
            'b_usuario_movil' => ['sometimes', 'boolean'],
        ];
    }
}
