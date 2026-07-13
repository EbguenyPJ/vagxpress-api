<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Perfil completo del usuario con los datos de su empleado.
 * Mantiene los nombres de campo que consume el frontend.
 */
class PerfilUsuarioResource extends JsonResource
{
    public function toArray($request): array
    {
        $empleado = $this->empleado;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            's_nombre_completo' => $this->s_nombre_completo,
            'id_empleado' => $this->id_empleado,
            'id_tipo_usuario' => $this->id_tipo_usuario,
            'b_usuario_web' => $this->b_usuario_web,
            'b_usuario_movil' => $this->b_usuario_movil,
            'b_activo' => $this->b_activo,
            's_nombre' => $empleado?->s_nombre,
            's_apellido_paterno' => $empleado?->s_apellido_paterno,
            's_apellido_materno' => $empleado?->s_apellido_materno,
            's_foto_empleado' => $empleado?->s_foto_empleado,
            's_rfc' => $empleado?->s_rfc,
            's_curp' => $empleado?->s_curp,
            's_telefono' => $empleado?->s_telefono,
            's_correo' => $empleado?->s_correo,
            's_direccion' => $empleado?->s_direccion,
            's_num_licencia' => $empleado?->s_num_licencia,
            's_num_seguro' => $empleado?->s_num_seguro,
            's_qr_empleado' => $empleado?->s_qr_empleado,
            'd_fecha_nacimiento' => $empleado?->d_fecha_nacimiento?->toDateString(),
            'd_fecha_ingreso' => $empleado?->d_fecha_ingreso?->toDateString(),
            's_comodin' => $empleado?->s_comodin,
            'id_sexo' => $empleado?->id_sexo,
            's_contacto_emergencia' => $empleado?->s_contacto_emergencia,
            's_telefono_contacto_emergencia' => $empleado?->s_telefono_contacto_emergencia,
            's_tipo_empleado' => $empleado?->tipoEmpleado?->s_tipo_empleado,
        ];
    }
}
