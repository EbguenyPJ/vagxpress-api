<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;
    protected $table = 'tw_alertas';
    protected $primaryKey = 'id_alerta';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_alerta',
        's_alerta',
        's_descripcion',
        'd_fecha_registro',
        't_hora_registro',
        'b_activo'
    ];
}
