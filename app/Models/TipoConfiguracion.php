<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_configuraciones';
    protected $primaryKey = 'id_tipo_configuracion';

    public const UTILIDAD_BASE = 1;

    protected $fillable = [
        'id_modulo',
        's_tipo_configuracion',
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

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function porcentajesUtilidad(): HasMany
    {
        return $this->hasMany(PorcentajeUtilidad::class, 'id_tipo_configuracion', 'id_tipo_configuracion');
    }
}
