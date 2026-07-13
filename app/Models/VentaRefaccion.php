<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_ventas_refacciones';
    protected $primaryKey = 'id_venta_refaccion';

    protected $fillable = [
        'n_cantidad',
        'n_costo_unitario',
        'n_porcentaje_utilidad',
        'n_total',
        'n_stock_previo',
        'n_stock_posterior',
        'id_venta',
        'id_refaccion',
        'b_activo',
    ];

    protected $casts = [
        'n_costo_unitario' => 'decimal:2',
        'n_porcentaje_utilidad' => 'decimal:2',
        'n_total' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }
}
