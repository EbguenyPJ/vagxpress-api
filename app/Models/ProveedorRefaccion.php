<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProveedorRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_proveedores_refacciones';
    protected $primaryKey = 'id_proveedor_refaccion';

    protected $fillable = [
        'id_proveedor',
        'id_refaccion',
        'n_ultimo_costo',
        'd_fecha_ultima_compra',
        's_sku_proveedor',
        's_no_parte_proveedor',
        's_codigo_qr_proveedor',
        'id_usuario_crea',
        'id_usuario_edita',
        'b_activo',
    ];

    protected $casts = [
        'n_ultimo_costo' => 'decimal:2',
        'd_fecha_ultima_compra' => 'date',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioEdita(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_edita', 'id');
    }
}
