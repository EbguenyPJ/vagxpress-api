<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Credito extends Model
{
    use HasFactory;

    protected $table = 'tw_creditos';
    protected $primaryKey = 'id_credito';

    protected $fillable = [
        'id_venta',
        's_comentario_credito',
        'n_total_a_pagar',
        'n_total_pagado',
        'id_tipo_credito',
        'id_estatus_credito',
        'id_usuario_crea',
        'id_usuario_modifica',
        'd_fecha_vencimiento',
        'b_activo',
    ];

    protected $casts = [
        'n_total_a_pagar' => 'decimal:2',
        'n_total_pagado' => 'decimal:2',
        'd_fecha_vencimiento' => 'date',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estatusCredito(): BelongsTo
    {
        return $this->belongsTo(EstatusCredito::class, 'id_estatus_credito', 'id_estatus_credito');
    }

    public function tipoCredito(): BelongsTo
    {
        return $this->belongsTo(TipoCredito::class, 'id_tipo_credito', 'id_tipo_credito');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class, 'id_credito', 'id_credito');
    }
}
