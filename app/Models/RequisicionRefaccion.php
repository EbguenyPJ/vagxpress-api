<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisicionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_requisiciones_refacciones';
    protected $primaryKey = 'id_requisicion_refaccion';

    protected $fillable = [
        'id_requisicion_refaccion',
        'id_requisicion',
        'id_refaccion',
        'n_cantidad_sugerida',
        'n_cantidad_solicitada',
        'n_costo_unitario',
        'id_motivo_pedido',
        'id_prioridad',
        'id_estatus_requisicion',
        'b_activo'
    ];
}
