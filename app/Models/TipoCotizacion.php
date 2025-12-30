<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCotizacion extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_cotizaciones';
    protected $primaryKey = 'id_tipo_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_cotizacion',
        'b_activo',
    ];
}
