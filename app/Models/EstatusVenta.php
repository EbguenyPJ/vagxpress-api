<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusVenta extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_ventas';
    protected $primaryKey = 'id_estatus_venta';

    public $timestamps = false;

    public const PAGADA = 1;
    public const CANCELADA = 2;
    public const PENDIENTE = 3;

    protected $fillable = [
        's_estatus_venta',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_estatus_venta', 'id_estatus_venta');
    }
}
