<?php

namespace App\Http\Requests\Empleados;

use Illuminate\Foundation\Http\FormRequest;

class CrearEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's_nombre' => ['required', 'string', 'max:255'],
            's_apellido_paterno' => ['required', 'string', 'max:255'],
            's_apellido_materno' => ['nullable', 'string', 'max:255'],
            's_telefono' => ['required', 'string', 'max:20'],
            's_telefono_contacto_emergencia' => ['nullable', 'string', 'max:20'],
            's_contacto_emergencia' => ['nullable', 'string', 'max:255'],
            's_correo' => ['nullable', 'email', 'max:255'],
            's_direccion' => ['nullable', 'string', 'max:255'],
            'd_fecha_nacimiento' => ['required', 'date'],
            'd_fecha_ingreso' => ['required', 'date'],
            'id_tipo_empleado' => ['required', 'integer', 'exists:tc_tipos_empleados,id_tipo_empleado'],
            'id_profesion' => ['nullable', 'integer', 'exists:tc_profesiones,id_profesion'],
            'id_grado_estudios' => ['nullable', 'integer', 'exists:tc_grados_estudios,id_grado_estudios'],
            'id_sucursal' => ['required', 'integer', 'exists:tw_sucursales,id_sucursal'],
            'id_sexo' => ['nullable', 'integer'],
            's_foto_empleado' => ['nullable', 'string'],
        ];
    }
}
