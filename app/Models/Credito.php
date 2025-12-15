<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    use HasFactory;

    protected $table = 'tw_creditos';
    protected $primaryKey = 'id_credito';

    protected $fillable = [
        'id_venta',
        's_comentario_credito',
        'n_total_a_pagar',
        'n_total_pagado',
        'id_tipo_credito',
        'id_estatus_credito',
        'id_usuario_crea',
        'id_usuario_modifica',
        'd_fecha_vencimiento',
        'b_activo'
    ];
}
