<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabilidadEmpleado extends Model
{
    use HasFactory;

    protected $table = 'tr_habilidades_empleados';
    protected $primaryKey = 'id_habilidad_empleado';

    public $timestamps = false;

    protected $fillable = [
        'id_habilidad',
        'id_empleado',
        'n_nivel_dominio',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function habilidad(): BelongsTo
    {
        return $this->belongsTo(Habilidad::class, 'id_habilidad', 'id_habilidad');
    }
}
