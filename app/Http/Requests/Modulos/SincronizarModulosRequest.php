<?php

namespace App\Http\Requests\Modulos;

use Illuminate\Foundation\Http\FormRequest;

class SincronizarModulosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modulos' => ['required', 'array'],
            'modulos.*' => ['integer', 'exists:tc_modulos,id_modulo'],
        ];
    }
}
