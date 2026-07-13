<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corte extends Model
{
    use HasFactory;

    protected $table = 'tw_cortes';
    protected $primaryKey = 'id_corte';

    protected $fillable = [
        'id_tipo_corte',
        'id_usuario_crea',
        'd_fecha_corte',
        'n_monto_efectivo',
        'n_monto_transferencia',
        'n_monto_credito',
        'n_monto_tarjeta_debito',
        'n_monto_tarjeta_credito',
        'n_monto_total',
        's_descripcion_corte',
        's_comentario',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_corte' => 'date',
        'n_monto_efectivo' => 'decimal:2',
        'n_monto_transferencia' => 'decimal:2',
        'n_monto_credito' => 'decimal:2',
        'n_monto_tarjeta_debito' => 'decimal:2',
        'n_monto_tarjeta_credito' => 'decimal:2',
        'n_monto_total' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function cortesEvidencias(): HasMany
    {
        return $this->hasMany(CorteEvidencia::class, 'id_corte', 'id_corte');
    }

    public function cortesVentas(): HasMany
    {
        return $this->hasMany(CorteVenta::class, 'id_corte', 'id_corte');
    }
}
