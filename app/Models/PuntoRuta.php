<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoRuta extends Model
{
    use HasFactory;
    protected $table = 'tw_puntos_ruta';
    protected $primaryKey = 'id_punto_ruta';
    protected $fillable = [
        'id_orden',
        'id_tipo_ruta',
        'n_latitud',
        'n_longitud',
        'timestamp',
        'b_activo',
    ];
}
