<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refaccion extends Model
{
    use HasFactory;

    protected $table = 'tw_refacciones';
    protected $primaryKey = 'id_refaccion';
    protected $fillable = [
        // Datos principales
        's_nombre_refaccion',
        's_descripcion',
        's_observaciones',
        's_numero_parte',
        's_codigo_interno',
        's_codigo_alterno',
        's_sku',
        's_codigo_aces',
        's_imagen_refaccion',
        's_codigo_qr',

            // Precios y costos
        'n_precio_compra',
        'n_precio_venta',
        'n_costo_promedio',
        'n_precio_mayoreo',
        'n_precio_minimo_autorizado',

            // Inventario
        'n_stock_actual',
        'n_stock_minimo',
        'n_stock_maximo',
        'n_tiempo_reposicion',

            // Relaciones (FKs)
        'id_marca_refaccion',
        'id_unidad_medida',
        'id_proveedor',
        'id_clase_refaccion',          // Niveles Calidad
        'id_categoria_refaccion',      //  Sistemas de ...
        'id_subcategoria_refaccion',       //  Tipos de ...
        'id_posicion_vehiculo',
        'id_ubicacion_almacen',
        'id_codigo_sat',
        'id_estatus_refaccion',
        'id_equivalencia',
        'id_codigo_aces',

            // Flags
        'b_importado',
        'b_activo',

            // Auditoría
        'id_usuario_crea',
        'id_usuario_edita',
    ];
}
