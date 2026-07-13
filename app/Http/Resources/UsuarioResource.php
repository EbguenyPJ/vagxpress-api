<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray($request): array
    {
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
            'created_at' => $this->created_at,
        ];
    }
}
