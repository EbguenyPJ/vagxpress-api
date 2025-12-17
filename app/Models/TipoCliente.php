<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_clientes';
    protected $primaryKey = 'id_tipo_cliente';

    protected $fillable = [
        's_tipo_cliente',
        'b_activo'
    ];
}
