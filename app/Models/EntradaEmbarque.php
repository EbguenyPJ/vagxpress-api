<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaEmbarque extends Model
{
    use HasFactory;
    protected $table = 'tr_entradas_embarque';
    protected $primaryKey = 'id_entrada_embarque';
    public $timestamps = false;
    protected $fillable = [
            'id_embarque',
            'id_refaccion',
            'id_pre_registro_refaccion',
            'id_estatus_entrada',
            'n_cantidad',
            'n_precio_compra',
            's_codigo_barras',
            'd_fecha_creacion',
            'b_activo'
    ];
}
