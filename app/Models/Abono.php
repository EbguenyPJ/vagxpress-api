<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'tw_abonos';
    protected $primaryKey = 'id_abono';

    protected $fillable = [
        'id_credito',
        's_referencia_pago',
        's_img_evidencia_pago',
        'n_saldo_venta_actual',
        'n_saldo_cliente_actual',
        'n_abono',
        'id_estatus_abono',
        'id_metodo_pago',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo'
    ];
}
