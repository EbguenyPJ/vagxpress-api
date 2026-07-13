<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PorcentajeUtilidad extends Model
{
    use HasFactory;

    protected $table = 'tc_porcentajes_utilidad';
    protected $primaryKey = 'id_porcentaje_utilidad';

    protected $fillable = [
        'id_tipo_configuracion',
        'n_porcentaje_utilidad',
        's_porcentaje_utilidad',
        's_descripcion',
        'b_activo',
    ];

    protected $casts = [
        'n_porcentaje_utilidad' => 'decimal:7',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tipoConfiguracion(): BelongsTo
    {
        return $this->belongsTo(TipoConfiguracion::class, 'id_tipo_configuracion', 'id_tipo_configuracion');
    }
}
