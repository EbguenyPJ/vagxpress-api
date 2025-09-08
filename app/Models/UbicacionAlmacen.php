<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionAlmacen extends Model
{
    use HasFactory;

    protected $table = 'tc_ubicaciones_almacen';
    protected $primaryKey = 'id_ubicacion_almacen';
    protected $fillable = [
        's_ubicacion_almacen',
        'b_activo',
    ];
}
