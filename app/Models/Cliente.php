<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'tw_clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        's_nombre_cliente',
        's_razon_social',
        's_rfc',
        's_ine',
        's_numero_telefono',
        's_correo',
        's_comentario',
        'n_saldo_actual',
        'n_limite_credito',
        'id_tipo_cliente',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_credito',
        'b_activo',
    ];

    protected $casts = [
        'n_saldo_actual' => 'decimal:2',
        'n_limite_credito' => 'decimal:2',
        'b_credito' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tipoCliente(): BelongsTo
    {
        return $this->belongsTo(TipoCliente::class, 'id_tipo_cliente', 'id_tipo_cliente');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_cliente', 'id_cliente');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_cliente', 'id_cliente');
    }
}
