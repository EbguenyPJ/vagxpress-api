<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusOrden extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_orden';
    protected $primaryKey = 'id_estatus_orden';

    public $timestamps = false;

    public const ASIGNADA = 1;
    public const EN_REPARTO = 2;
    public const ENTREGADA = 3;
    public const PENDIENTE_ASIGNACION = 4;

    protected $fillable = [
        's_estatus_orden',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class, 'id_estatus_orden', 'id_estatus_orden');
    }
}
