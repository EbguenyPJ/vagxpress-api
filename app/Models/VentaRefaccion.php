<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_ventas_refacciones';
    protected $primaryKey = 'id_venta_refaccion';

    protected $fillable = [
        'n_cantidad',
        'n_costo_unitario',
        'n_porcentaje_utilidad',
        'n_total',
        'n_stock_previo',
        'n_stock_posterior',
        'id_venta',
        'id_refaccion',
        'b_activo',
    ];
}
