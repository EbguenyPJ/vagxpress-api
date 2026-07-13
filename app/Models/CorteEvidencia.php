<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorteEvidencia extends Model
{
    use HasFactory;

    protected $table = 'tw_cortes_evidencias';
    protected $primaryKey = 'id_corte_evidencia';

    protected $fillable = [
        'id_corte',
        'id_metodo_pago',
        'id_tipo_evidencia',
        's_nombre_archivo',
        's_descripcion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function corte(): BelongsTo
    {
        return $this->belongsTo(Corte::class, 'id_corte', 'id_corte');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    public function tipoEvidencia(): BelongsTo
    {
        return $this->belongsTo(TipoEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }
}
