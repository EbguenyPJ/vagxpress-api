<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_ordenes_compras';
    protected $primaryKey = 'id_estatus_orden_compra';

    public $timestamps = false;

    protected $fillable = [
        'id_estatus_orden_compra',
        'b_activo'
    ];
}
