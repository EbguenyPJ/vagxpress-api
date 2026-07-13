<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_cliente' => $this->id_cliente,
            's_nombre_cliente' => $this->s_nombre_cliente,
            's_razon_social' => $this->s_razon_social,
            's_rfc' => $this->s_rfc,
            's_ine' => $this->s_ine,
            's_numero_telefono' => $this->s_numero_telefono,
            's_correo' => $this->s_correo,
            's_comentario' => $this->s_comentario,
            'n_saldo_actual' => $this->n_saldo_actual,
            'n_limite_credito' => $this->n_limite_credito,
            'id_tipo_cliente' => $this->id_tipo_cliente,
            's_tipo_cliente' => $this->whenLoaded('tipoCliente', fn () => $this->tipoCliente?->s_tipo_cliente),
            'b_credito' => $this->b_credito,
            'b_activo' => $this->b_activo,
        ];
    }
}
