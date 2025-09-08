<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'tc_modulos';
    protected $primaryKey = 'id_modulo';
    protected $fillable = [
        'id_categoria_modulo',
        's_modulo',
        's_ruta',
        's_icono',
        'b_activo'
    ];
}
