<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoPedido extends Model
{
    use HasFactory;

    protected $table = 'tc_motivos_pedidos';
    protected $primaryKey = 'id_motivo_pedido';

    public $timestamps = false;

    protected $fillable = [
        's_motivo_pedido',
        'b_activo'
    ];
}
