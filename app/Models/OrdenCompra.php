<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'tw_ordenes_compras';
    protected $primaryKey = 'id_orden_compra';

    protected $fillable = [
        's_folio_interno',
        's_observacion',
        'd_fecha_orden',
        'd_fecha_recepcion_estimada',
        'n_total_estimado',
        'id_proveedor',
        'id_requisicion',
        'id_estatus_orden_compra',
        'id_usuario_crea',
        'id_usuario_modifica',
        'id_usuario_autoriza',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_orden' => 'date',
        'd_fecha_recepcion_estimada' => 'date',
        'n_total_estimado' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estatusOrdenCompra(): BelongsTo
    {
        return $this->belongsTo(EstatusOrdenCompra::class, 'id_estatus_orden_compra', 'id_estatus_orden_compra');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'id_requisicion', 'id_requisicion');
    }

    public function usuarioAutoriza(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_autoriza', 'id');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function ordenesComprasRequisicionesRefacciones(): HasMany
    {
        return $this->hasMany(OrdenCompraRequisicionRefaccion::class, 'id_orden_compra', 'id_orden_compra');
    }
}
