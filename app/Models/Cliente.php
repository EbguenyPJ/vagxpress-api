<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'tw_clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        's_nombre_cliente',
        's_razon_social',
        's_rfc',
        's_ine',
        's_numero_telefono',
        's_correo',
        's_comentario',
        'n_saldo_actual',
        'n_limite_credito',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_credito',
        'b_activo'
    ];
}
