<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequisicionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_requisiciones_refacciones';
    protected $primaryKey = 'id_requisicion_refaccion';

    protected $fillable = [
        'id_requisicion',
        'id_refaccion',
        'n_cantidad_sugerida',
        'n_cantidad_solicitada',
        'n_costo_unitario',
        'id_motivo_pedido',
        'id_prioridad',
        'id_estatus_requisicion',
        'b_activo',
    ];

    protected $casts = [
        'n_costo_unitario' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estatusRequisicion(): BelongsTo
    {
        return $this->belongsTo(EstatusRequisicion::class, 'id_estatus_requisicion', 'id_estatus_requisicion');
    }

    public function motivoPedido(): BelongsTo
    {
        return $this->belongsTo(MotivoPedido::class, 'id_motivo_pedido', 'id_motivo_pedido');
    }

    public function prioridad(): BelongsTo
    {
        return $this->belongsTo(Prioridad::class, 'id_prioridad', 'id_prioridad');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'id_requisicion', 'id_requisicion');
    }

    public function ordenesComprasRequisicionesRefacciones(): HasMany
    {
        return $this->hasMany(OrdenCompraRequisicionRefaccion::class, 'id_requisicion_refaccion', 'id_requisicion_refaccion');
    }
}
