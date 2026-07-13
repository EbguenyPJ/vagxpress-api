<?php

namespace App\Http\Requests\Clientes;

use Illuminate\Foundation\Http\FormRequest;

class GuardarClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's_nombre_cliente' => ['required', 'string', 'max:255'],
            's_razon_social' => ['nullable', 'string', 'max:255'],
            's_rfc' => ['nullable', 'string', 'max:20'],
            's_ine' => ['nullable', 'string', 'max:50'],
            's_numero_telefono' => ['nullable', 'string', 'max:20'],
            's_correo' => ['nullable', 'email', 'max:255'],
            's_comentario' => ['nullable', 'string', 'max:255'],
            'id_tipo_cliente' => ['required', 'integer', 'exists:tc_tipos_clientes,id_tipo_cliente'],
            'b_credito' => ['sometimes', 'boolean'],
            'n_limite_credito' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
