<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModeloVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tc_modelos_vehiculos';
    protected $primaryKey = 'id_modelo_vehiculo';

    protected $fillable = [
        'id_marca_vehiculo',
        's_modelo_vehiculo',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function marcaVehiculo(): BelongsTo
    {
        return $this->belongsTo(MarcaVehiculo::class, 'id_marca_vehiculo', 'id_marca_vehiculo');
    }

    public function generaciones(): HasMany
    {
        return $this->hasMany(Generacion::class, 'id_modelo_vehiculo', 'id_modelo_vehiculo');
    }

    public function reglasModelos(): HasMany
    {
        return $this->hasMany(ReglaModelo::class, 'id_modelo_vehiculo', 'id_modelo_vehiculo');
    }
}
