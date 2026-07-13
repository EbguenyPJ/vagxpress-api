<?php

namespace App\Http\Requests\Empleados;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's_nombre' => ['sometimes', 'string', 'max:255'],
            's_apellido_paterno' => ['sometimes', 'string', 'max:255'],
            's_apellido_materno' => ['sometimes', 'nullable', 'string', 'max:255'],
            's_telefono' => ['sometimes', 'string', 'max:20'],
            's_telefono_contacto_emergencia' => ['sometimes', 'nullable', 'string', 'max:20'],
            's_contacto_emergencia' => ['sometimes', 'nullable', 'string', 'max:255'],
            's_correo' => ['sometimes', 'nullable', 'email', 'max:255'],
            's_direccion' => ['sometimes', 'nullable', 'string', 'max:255'],
            'd_fecha_nacimiento' => ['sometimes', 'date'],
            'd_fecha_ingreso' => ['sometimes', 'date'],
            'id_tipo_empleado' => ['sometimes', 'integer', 'exists:tc_tipos_empleados,id_tipo_empleado'],
            'id_profesion' => ['sometimes', 'nullable', 'integer', 'exists:tc_profesiones,id_profesion'],
            'id_grado_estudios' => ['sometimes', 'nullable', 'integer', 'exists:tc_grados_estudios,id_grado_estudios'],
            'id_sucursal' => ['sometimes', 'integer', 'exists:tw_sucursales,id_sucursal'],
            'id_sexo' => ['sometimes', 'nullable', 'integer'],
            's_foto_empleado' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
