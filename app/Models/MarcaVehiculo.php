<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarcaVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tc_marcas_vehiculos';
    protected $primaryKey = 'id_marca_vehiculo';

    protected $fillable = [
        's_marca_vehiculo',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function modelosVehiculos(): HasMany
    {
        return $this->hasMany(ModeloVehiculo::class, 'id_marca_vehiculo', 'id_marca_vehiculo');
    }

    public function reglasMarcas(): HasMany
    {
        return $this->hasMany(ReglaMarca::class, 'id_marca_vehiculo', 'id_marca_vehiculo');
    }
}
