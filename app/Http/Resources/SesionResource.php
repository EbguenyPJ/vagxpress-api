<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Payload de sesión tras un login exitoso.
 * Envuelve ['token' => string, 'user' => User (con empleado.tipoEmpleado)].
 */
class SesionResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->resource['user'];
        $empleado = $user->empleado;

        return [
            'token' => $this->resource['token'],
            'id_usuario' => $user->id,
            'username' => $user->name,
            's_nombre_completo' => $user->s_nombre_completo,
            'id_empleado' => $user->id_empleado,
            's_foto_empleado' => $empleado?->s_foto_empleado,
            'id_tipo_usuario' => $user->id_tipo_usuario,
            'id_tipo_empleado' => $empleado?->id_tipo_empleado,
            's_tipo_empleado' => $empleado?->tipoEmpleado?->s_tipo_empleado ?? 'No especificado',
            'id_sucursal' => $empleado?->id_sucursal,
        ];
    }
}
