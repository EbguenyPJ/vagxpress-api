<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    use HasFactory;

    protected $table = 'tc_cuentas_bancarias';
    protected $primaryKey = 'id_cuenta_bancaria';

    public $timestamps = false;

    protected $fillable = [
        's_nombre_cuenta',
        'n_numero_cuenta',
        'n_numero_tarjeta',
        'n_CLABE',
        'id_metodo_pago',
        'id_tipo_cuenta',
        'id_banco',
        'b_activo',
    ];
}
