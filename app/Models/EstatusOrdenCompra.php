<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_ordenes_compras';
    protected $primaryKey = 'id_estatus_orden_compra';

    public $timestamps = false;

    public const CREADA = 1;
    public const APROBADA = 2;
    public const RECHAZADA = 3;

    protected $fillable = [
        's_estatus_orden_compra',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function ordenesCompras(): HasMany
    {
        return $this->hasMany(OrdenCompra::class, 'id_estatus_orden_compra', 'id_estatus_orden_compra');
    }
}
