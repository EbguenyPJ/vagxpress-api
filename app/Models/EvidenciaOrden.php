<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidenciaOrden extends Model
{
    use HasFactory;

    protected $table = 'tw_evidencias_orden';
    protected $primaryKey = 'id_evidencia_orden';

    public $timestamps = false;

    protected $fillable = [
        'id_orden',
        's_evidencia_orden',
        'id_tipo_evidencia',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }

    public function tipoEvidencia(): BelongsTo
    {
        return $this->belongsTo(TipoEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }
}
