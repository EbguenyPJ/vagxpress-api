<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'tw_gastos';
    protected $primaryKey = 'id_gasto';

    public $timestamps = false;

    protected $fillable = [
        'id_tipo_gasto',
        'id_tipo_evidencia',
        'id_sucursal',
        'n_cantidad',
        'n_costo',
        's_concepto',
        's_evidencia',
        'd_fecha_gasto',
        'd_fecha_creacion',
        'id_usuario_crea',
        'b_movil',
        'b_activo',
    ];

    protected $casts = [
        'n_costo' => 'decimal:2',
        'd_fecha_gasto' => 'datetime',
        'd_fecha_creacion' => 'datetime',
        'b_movil' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function tipoEvidencia(): BelongsTo
    {
        return $this->belongsTo(TipoEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }

    public function tipoGasto(): BelongsTo
    {
        return $this->belongsTo(TipoGasto::class, 'id_tipo_gasto', 'id_tipo_gasto');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }
}
