<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoriaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_subcategorias_refacciones';
    protected $primaryKey = 'id_subcategoria_refaccion';
    protected $fillable = [
        'id_categoria_refaccion',
        's_subcategoria_refaccion',
        'b_activo',
    ];
}
