<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarEstatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'b_activo' => ['required', 'boolean'],
        ];
    }
}
