<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'tw_abonos';
    protected $primaryKey = 'id_abono';

    protected $fillable = [
        'id_credito',
        's_referencia_pago',
        's_img_evidencia_pago',
        'n_saldo_venta_actual',
        'n_saldo_cliente_actual',
        'n_abono',
        'id_estatus_abono',
        'id_metodo_pago',
        'id_usuario_crea',
        'id_usuario_modifica',
        'b_activo',
    ];

    protected $casts = [
        'n_saldo_venta_actual' => 'decimal:2',
        'n_saldo_cliente_actual' => 'decimal:2',
        'n_abono' => 'decimal:2',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function credito(): BelongsTo
    {
        return $this->belongsTo(Credito::class, 'id_credito', 'id_credito');
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
}
