<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCuenta extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_cuentas';
    protected $primaryKey = 'id_tipo_cuenta';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_cuenta',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cuentasBancarias(): HasMany
    {
        return $this->hasMany(CuentaBancaria::class, 'id_tipo_cuenta', 'id_tipo_cuenta');
    }
}
