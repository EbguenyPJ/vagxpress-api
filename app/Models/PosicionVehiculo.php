<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicionVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tc_posiciones_vehiculo';
    protected $primaryKey = 'id_posicion_vehiculo';
    protected $fillable = [
        's_posicion_vehiculo',
        'b_activo',
    ];
}
