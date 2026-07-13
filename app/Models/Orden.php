<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'tw_ordenes';
    protected $primaryKey = 'id_orden';

    public $timestamps = false;

    protected $fillable = [
        'id_destino',
        's_nota_refaccionista',
        'id_repartidor',
        'd_fecha_asignacion',
        'id_estatus_orden',
        'd_fecha_entrega',
        's_nombre_recibe',
        's_firma',
        'd_fin_regreso',
        'd_inicio_regreso',
        'd_fin_reparto',
        'd_inicio_reparto',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_asignacion' => 'datetime',
        'd_fecha_entrega' => 'datetime',
        'd_fin_regreso' => 'datetime',
        'd_inicio_regreso' => 'datetime',
        'd_fin_reparto' => 'datetime',
        'd_inicio_reparto' => 'datetime',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'id_destino', 'id_destino');
    }

    public function estatusOrden(): BelongsTo
    {
        return $this->belongsTo(EstatusOrden::class, 'id_estatus_orden', 'id_estatus_orden');
    }

    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_repartidor', 'id_empleado');
    }

    public function puntosRuta(): HasMany
    {
        return $this->hasMany(PuntoRuta::class, 'id_orden', 'id_orden');
    }

    public function evidenciasOrden(): HasMany
    {
        return $this->hasMany(EvidenciaOrden::class, 'id_orden', 'id_orden');
    }

    public function ordenesProductos(): HasMany
    {
        return $this->hasMany(OrdenProducto::class, 'id_orden', 'id_orden');
    }
}
