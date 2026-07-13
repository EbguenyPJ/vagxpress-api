<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidenciaEmbarque extends Model
{
    use HasFactory;

    protected $table = 'tw_evidencias_embarque';
    protected $primaryKey = 'id_evidencia_embarque';

    public $timestamps = false;

    protected $fillable = [
        'id_embarque',
        'id_tipo_evidencia',
        's_evidencia_embarque',
        'd_fecha_creacion',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_creacion' => 'datetime',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function embarque(): BelongsTo
    {
        return $this->belongsTo(Embarque::class, 'id_embarque', 'id_embarque');
    }

    public function tipoEvidencia(): BelongsTo
    {
        return $this->belongsTo(TipoEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }
}
