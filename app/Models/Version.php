<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;
    protected $table = 'tw_versiones';
    protected $primaryKey = 'id_version';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_usuario',
        's_nombre_version',
        's_descripcion_version',
        'd_fecha_actualizacion_version',
        'b_activo'
    ];
}
