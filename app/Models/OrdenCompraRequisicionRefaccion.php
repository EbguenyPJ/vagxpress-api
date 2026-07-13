<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenCompraRequisicionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_ordenes_compras_requisiciones_refacciones';
    protected $primaryKey = 'id_orden_compra_requisicion_refaccion';

    protected $fillable = [
        'id_orden_compra',
        'id_requisicion_refaccion',
        'n_cantidad_recibida',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class, 'id_orden_compra', 'id_orden_compra');
    }

    public function requisicionRefaccion(): BelongsTo
    {
        return $this->belongsTo(RequisicionRefaccion::class, 'id_requisicion_refaccion', 'id_requisicion_refaccion');
    }
}
