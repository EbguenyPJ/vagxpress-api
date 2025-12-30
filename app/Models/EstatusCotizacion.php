<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusCotizacion extends Model
{
    use HasFactory;

    protected $table = "tc_estatus_cotizaciones";
    protected $primaryKey = 'id_estatus_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        's_estatus_cotizacion',
        'b_activo',
    ];
}
