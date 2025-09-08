<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaModulo extends Model
{
    use HasFactory;

    protected $table = 'tc_categorias_modulos';
    protected $primaryKey = 'id_categoria_modulo';
    protected $fillable =
        [
            's_categoria_modulo',
            'b_activo'
        ];
}
