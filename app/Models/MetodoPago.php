<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'tc_metodos_pagos';
    protected $primaryKey = 'id_metodo_pago';

    protected $fillable = [
        'id_metodo_pago',
        'id_tipo_pago',
        's_metodo_pago',
        's_img_metodo_pago',
        's_descripcion_metodo_pago',
        'b_requiere_referencia',
        'b_requiere_evidencia',
        'b_activo',
    ];
}
