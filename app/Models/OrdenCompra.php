<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'tw_ordenes_compras';
    protected $primaryKey = 'id_orden_compra';

    protected $fillable = [
        's_folio_interno',
        's_observacion',
        'd_fecha_orden',
        'd_fecha_recepcion_estimada',
        'n_total_estimado',
        'id_proveedor',
        'id_requisicion',
        'id_estatus_orden_compra',
        'id_usuario_crea',
        'id_usuario_modifica',
        'id_usuario_autoriza',
        'b_activo',
    ];
}
