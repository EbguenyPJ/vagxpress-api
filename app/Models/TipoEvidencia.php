<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEvidencia extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_evidencias';
    protected $primaryKey = 'id_tipo_evidencia';

    public $timestamps = false;

    public const IMAGEN_GENERAL = 1;
    public const IMAGEN_FACTURA = 2;
    public const PDF_FACTURA = 3;

    protected $fillable = [
        's_tipo_evidencia',
        's_mime_type',
        's_extension',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function evidenciasEmbarque(): HasMany
    {
        return $this->hasMany(EvidenciaEmbarque::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }

    public function cortesEvidencias(): HasMany
    {
        return $this->hasMany(CorteEvidencia::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }

    public function evidenciasOrden(): HasMany
    {
        return $this->hasMany(EvidenciaOrden::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'id_tipo_evidencia', 'id_tipo_evidencia');
    }
}
