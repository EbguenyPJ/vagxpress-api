<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCuenta extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_cuentas';
    protected $primaryKey = 'id_tipo_cuenta';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_cuenta',
        'b_activo',
    ];
}
