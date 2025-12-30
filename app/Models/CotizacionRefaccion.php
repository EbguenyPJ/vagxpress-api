<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_cotizaciones_refacciones';
    protected $primaryKey = 'id_cotizacion_refaccion';

    protected $fillable = [
        'id_cotizacion',
        'id_refaccion',
        'n_cantidad',
        'n_costo_unitario',
        'n_porcentaje_utilidad',
        'n_total',
        'b_activo'
    ];
}
