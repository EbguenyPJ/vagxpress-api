<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'tw_ventas';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'n_subtotal',
        'n_porcentaje_iva',
        'n_total',
        'n_cantidad_refacciones',
        'id_estatus_venta',
        'id_cliente',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo'
    ];
}
