<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegistroRefaccion extends Model
{
    use HasFactory;
    protected $table = 'tw_pre_registro_refacciones';
    protected $primaryKey = 'id_pre_registro_refaccion';
    public $timestamps = false;
    protected $fillable = [
            's_nombre_refaccion',
            's_numero_parte',
            'id_marca_refaccion',
            'id_categoria_refaccion',
            'id_subcategoria_refaccion',
            'id_clase_refaccion',
            'n_precio_compra',
            'id_usuario_crea',
            'b_activo'
    ];
}
