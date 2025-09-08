<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_categorias_refacciones';
    protected $primaryKey = 'id_categoria_refaccion';
    protected $fillable = [
        's_categoria_refaccion',
        'b_activo',
    ];
}
