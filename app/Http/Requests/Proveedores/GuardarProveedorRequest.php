<?php

namespace App\Http\Requests\Proveedores;

use Illuminate\Foundation\Http\FormRequest;

class GuardarProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's_proveedor' => ['required', 'string', 'max:255'],
            's_nombre_contacto' => ['nullable', 'string', 'max:255'],
            's_telefono' => ['nullable', 'string', 'max:20'],
            's_rfc' => ['nullable', 'string', 'max:20'],
            's_img_proveedor' => ['nullable', 'string'],
        ];
    }
}
