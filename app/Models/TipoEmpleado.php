<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEmpleado extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_empleados';
    protected $primaryKey = 'id_tipo_empleado';

    protected $fillable = [
        's_tipo_empleado',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function habilidades(): HasMany
    {
        return $this->hasMany(Habilidad::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }

    public function descripcionesTiposEmpleados(): HasMany
    {
        return $this->hasMany(DescripcionTipoEmpleado::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }
}
