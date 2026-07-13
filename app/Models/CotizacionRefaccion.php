<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CotizacionRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_cotizaciones_refacciones';
    protected $primaryKey = 'id_cotizacion_refaccion';

    protected $fillable = [
        'id_cotizacion',
        'id_refaccion',
        'n_cantidad',
        'n_costo_unitario',
        'n_porcentaje_utilidad',
        'n_total',
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

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
