<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** Renglón de la bitácora de ventas. */
class VentaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_venta' => $this->id_venta,
            'n_subtotal' => $this->n_subtotal,
            'n_porcentaje_iva' => $this->n_porcentaje_iva,
            'n_total' => $this->n_total,
            'n_cantidad_refacciones' => $this->n_cantidad_refacciones,
            'id_estatus_venta' => $this->id_estatus_venta,
            's_estatus_venta' => $this->estatusVenta?->s_estatus_venta,
            'id_metodo_pago' => $this->id_metodo_pago,
            's_metodo_pago' => $this->metodoPago?->s_metodo_pago,
            'id_cliente' => $this->id_cliente,
            's_nombre_cliente' => $this->cliente?->s_nombre_cliente,
            'fecha_venta' => $this->created_at,
        ];
    }
}
