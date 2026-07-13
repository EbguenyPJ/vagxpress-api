<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** Detalle completo de una refacción con catálogos y equivalencias. */
class RefaccionDetalleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_refaccion' => $this->id_refaccion,
            's_nombre_refaccion' => $this->s_nombre_refaccion,
            's_descripcion' => $this->s_descripcion,
            's_numero_parte' => $this->s_numero_parte,
            's_codigo_interno' => $this->s_codigo_interno,
            's_imagen_refaccion' => $this->s_imagen_refaccion,
            'n_precio_compra' => $this->n_precio_compra,
            'n_precio_venta' => $this->n_precio_venta,
            'n_precio_mayoreo' => $this->n_precio_mayoreo,
            'n_stock_actual' => $this->n_stock_actual,
            'n_stock_minimo' => $this->n_stock_minimo,
            'n_tiempo_reposicion' => $this->n_tiempo_reposicion,
            'id_marca_refaccion' => $this->id_marca_refaccion,
            's_marca_refaccion' => $this->marcaRefaccion?->s_marca_refaccion,
            'id_unidad_medida' => $this->id_unidad_medida,
            's_unidad_medida' => $this->unidadMedida?->s_unidad_medida,
            'id_proveedor' => $this->id_proveedor,
            's_proveedor' => $this->proveedor?->s_proveedor,
            'id_categoria_refaccion' => $this->id_categoria_refaccion,
            's_categoria_refaccion' => $this->categoriaRefaccion?->s_categoria_refaccion,
            'id_subcategoria_refaccion' => $this->id_subcategoria_refaccion,
            's_subcategoria_refaccion' => $this->subcategoriaRefaccion?->s_subcategoria_refaccion,
            'id_posicion_vehiculo' => $this->id_posicion_vehiculo,
            's_posicion_vehiculo' => $this->posicionVehiculo?->s_posicion_vehiculo,
            'id_ubicacion_almacen' => $this->id_ubicacion_almacen,
            's_ubicacion_almacen' => $this->ubicacionAlmacen?->s_ubicacion_almacen,
            'id_estatus_refaccion' => $this->id_estatus_refaccion,
            's_estatus_refaccion' => $this->estatusRefaccion?->s_estatus_refaccion,
            'b_importado' => $this->b_importado,
            'id_clase_refaccion' => $this->id_clase_refaccion,
            's_clase_refaccion' => $this->claseRefaccion?->s_clase_refaccion,
            'refacciones_equivalentes' => $this->refacciones_equivalentes,
        ];
    }
}
