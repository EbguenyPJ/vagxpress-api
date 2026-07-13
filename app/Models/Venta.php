<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'tw_ventas';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'n_subtotal',
        'n_porcentaje_iva',
        'n_total',
        'n_cantidad_refacciones',
        'id_estatus_venta',
        'id_metodo_pago',
        'id_cliente',
        'id_cuenta_bancaria',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo',
        'b_corte',
    ];

    protected $casts = [
        'n_subtotal' => 'decimal:2',
        'n_porcentaje_iva' => 'decimal:7',
        'n_total' => 'decimal:2',
        'b_activo' => 'boolean',
        'b_corte' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function cuentaBancaria(): BelongsTo
    {
        return $this->belongsTo(CuentaBancaria::class, 'id_cuenta_bancaria', 'id_cuenta_bancaria');
    }

    public function estatusVenta(): BelongsTo
    {
        return $this->belongsTo(EstatusVenta::class, 'id_estatus_venta', 'id_estatus_venta');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function ventasRefacciones(): HasMany
    {
        return $this->hasMany(VentaRefaccion::class, 'id_venta', 'id_venta');
    }

    public function creditos(): HasMany
    {
        return $this->hasMany(Credito::class, 'id_venta', 'id_venta');
    }

    public function cortesVentas(): HasMany
    {
        return $this->hasMany(CorteVenta::class, 'id_venta', 'id_venta');
    }
}
