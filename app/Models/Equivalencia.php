<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equivalencia extends Model
{
    use HasFactory;

    protected $table = 'tw_equivalencias';
    protected $primaryKey = 'id_equivalencia';
    protected $fillable = [
        's_nombre_equivalencia',
        's_descripcion_equivalencia',
        'id_usuario_crea',
        'id_usuario_edita',
        'b_activo',
    ];
}
