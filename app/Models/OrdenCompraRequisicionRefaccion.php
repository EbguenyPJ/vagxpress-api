<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompraRequisicionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tw_ordenes_compras_requisicion_refacciones';
    protected $primaryKey = 'id_orden_compra_requisicion_refaccion';

    protected $fillable = [
        'id_orden_compra',
        'id_requisicion_refaccion',
        'n_cantidad_recibida',
        'b_activo'
    ];
}
