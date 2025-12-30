<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'tw_cotizaciones';
    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'n_subtotal',
        'n_porcentaje_iva',
        'n_total',
        'n_cantidad_refacciones',
        'id_estatus_cotizacion',
        'id_tipo_cotizacion',
        'id_cliente',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo',
    ];
}
