<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'tw_cotizaciones';
    protected $primaryKey = 'id_cotizacion';

    protected $fillable = [
        'n_subtotal',
        'n_porcentaje_iva',
        'n_total',
        'n_cantidad_refacciones',
        'id_estatus_cotizacion',
        'id_tipo_cotizacion',
        'id_cliente',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo',
    ];

    protected $casts = [
        'n_subtotal' => 'decimal:2',
        'n_porcentaje_iva' => 'decimal:7',
        'n_total' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function estatusCotizacion(): BelongsTo
    {
        return $this->belongsTo(EstatusCotizacion::class, 'id_estatus_cotizacion', 'id_estatus_cotizacion');
    }

    public function tipoCotizacion(): BelongsTo
    {
        return $this->belongsTo(TipoCotizacion::class, 'id_tipo_cotizacion', 'id_tipo_cotizacion');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function cotizacionesRefacciones(): HasMany
    {
        return $this->hasMany(CotizacionRefaccion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
