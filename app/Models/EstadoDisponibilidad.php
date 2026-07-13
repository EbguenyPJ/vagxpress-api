<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoDisponibilidad extends Model
{
    use HasFactory;

    protected $table = 'tc_estados_disponibilidad';
    protected $primaryKey = 'id_estado_disponibilidad';

    public $timestamps = false;

    public const DISPONIBLE = 1;
    public const EN_SERVICIO = 2;
    public const INCAPACITADO = 3;
    public const PERMISO = 4;
    public const VACACIONES = 5;

    protected $fillable = [
        's_estado_disponibilidad',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_estado_disponibilidad', 'id_estado_disponibilidad');
    }
}
