<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table = 'tw_ordenes';
    protected $primaryKey = 'id_orden';
    public $timestamps = false;
    protected $fillable = [
            'id_destino',
            's_nota_refaccionistas',
            'id_repartidor',
            'd_fecha_asignacion',
            'id_estatus_orden',
            'd_fecha_entrega',
            's_nombre_recibe',
            's_firma',
            'b_activo'
    ];
}
