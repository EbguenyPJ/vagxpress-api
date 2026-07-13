<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habilidad extends Model
{
    use HasFactory;

    protected $table = 'tc_habilidades';
    protected $primaryKey = 'id_habilidad';

    public $timestamps = false;

    protected $fillable = [
        'id_tipo_empleado',
        's_habilidad_empleado',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tipoEmpleado(): BelongsTo
    {
        return $this->belongsTo(TipoEmpleado::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }

    public function habilidadesEmpleados(): HasMany
    {
        return $this->hasMany(HabilidadEmpleado::class, 'id_habilidad', 'id_habilidad');
    }
}
