<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** Tarjeta del catálogo de refacciones. */
class RefaccionListadoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_refaccion' => $this->id_refaccion,
            's_nombre_refaccion' => $this->s_nombre_refaccion,
            's_numero_parte' => $this->s_numero_parte,
            'n_precio_venta' => $this->n_precio_venta,
            's_codigo_qr' => $this->s_codigo_qr,
            's_marca_refaccion' => $this->marcaRefaccion?->s_marca_refaccion,
            's_categoria_refaccion' => $this->categoriaRefaccion?->s_categoria_refaccion,
            'id_subcategoria_refaccion' => $this->id_subcategoria_refaccion,
            's_subcategoria_refaccion' => $this->subcategoriaRefaccion?->s_subcategoria_refaccion,
            's_imagen_refaccion' => $this->s_imagen_refaccion,
            'n_stock_actual' => $this->n_stock_actual,
            'id_estatus_refaccion' => $this->id_estatus_refaccion,
            's_estatus_refaccion' => $this->estatusRefaccion?->s_estatus_refaccion,
            's_color_estatus_refaccion' => $this->estatusRefaccion?->s_color_estatus_refaccion,
            'id_clase_refaccion' => $this->id_clase_refaccion,
            's_clase_refaccion' => $this->claseRefaccion?->s_clase_refaccion,
            's_color_clase_refaccion' => $this->claseRefaccion?->s_color_clase_refaccion,
        ];
    }
}
