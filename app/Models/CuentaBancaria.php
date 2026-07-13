<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaBancaria extends Model
{
    use HasFactory;

    protected $table = 'tc_cuentas_bancarias';
    protected $primaryKey = 'id_cuenta_bancaria';

    public $timestamps = false;

    protected $fillable = [
        's_nombre_cuenta',
        'n_numero_cuenta',
        'n_numero_tarjeta',
        'n_CLABE',
        'id_metodo_pago',
        'id_tipo_cuenta',
        'id_banco',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class, 'id_banco', 'id_banco');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function tipoCuenta(): BelongsTo
    {
        return $this->belongsTo(TipoCuenta::class, 'id_tipo_cuenta', 'id_tipo_cuenta');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_cuenta_bancaria', 'id_cuenta_bancaria');
    }
}
