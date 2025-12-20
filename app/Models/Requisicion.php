<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisicion extends Model
{
    use HasFactory;

    protected $table = 'tw_requisiciones';
    protected $primaryKey = 'id_requisicion';

    protected $fillable = [
        's_observacion',
        'n_cantidad_refacciones',
        'n_total_estimado',
        'd_fecha_limite',
        'd_fecha_solicitud',
        'id_estatus_requisicion',
        'id_tipo_requisicion',
        'id_usuario_crea',
        'id_usuario_modifica',
        'id_usuario_autoriza',
        'b_activo',
    ];
}
