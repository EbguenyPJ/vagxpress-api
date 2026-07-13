<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorteVenta extends Model
{
    use HasFactory;

    protected $table = 'tr_cortes_ventas';
    protected $primaryKey = 'id_corte_ventas';

    protected $fillable = [
        'id_corte',
        'id_venta',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function corte(): BelongsTo
    {
        return $this->belongsTo(Corte::class, 'id_corte', 'id_corte');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }
}
