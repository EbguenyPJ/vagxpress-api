<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorcentajeUtilidad extends Model
{
    use HasFactory;

    protected $table = 'tc_porcentajes_utilidad';
    protected $primaryKey = 'id_porcentaje_utilidad';

    public $timestamps = false;

    protected $fillable = [
        'id_tipo_configuracion',
        'n_porcentaje_utilidad',
        's_porcentaje_utilidad',
        's_descripcion',
        'b_actico',
    ];
}
