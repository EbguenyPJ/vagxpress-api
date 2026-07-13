<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'tc_metodos_pagos';
    protected $primaryKey = 'id_metodo_pago';

    public const CREDITO = 1;
    public const EFECTIVO = 2;
    public const TARJETA_CREDITO = 3;
    public const TARJETA_DEBITO = 4;
    public const TRANSFERENCIA = 5;

    protected $fillable = [
        's_metodo_pago',
        's_img_metodo_pago',
        's_descripcion_metodo_pago',
        'b_requiere_referencia',
        'b_requiere_evidencia',
        'b_activo',
    ];

    protected $casts = [
        'b_requiere_referencia' => 'boolean',
        'b_requiere_evidencia' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cortesEvidencias(): HasMany
    {
        return $this->hasMany(CorteEvidencia::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function cuentasBancarias(): HasMany
    {
        return $this->hasMany(CuentaBancaria::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_metodo_pago', 'id_metodo_pago');
    }
}
