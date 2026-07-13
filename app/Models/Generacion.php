<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generacion extends Model
{
    use HasFactory;

    protected $table = 'tc_generaciones';
    protected $primaryKey = 'id_generacion';

    protected $fillable = [
        'id_modelo_vehiculo',
        's_generacion',
        'n_anio_inicio',
        'n_anio_fin',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function modeloVehiculo(): BelongsTo
    {
        return $this->belongsTo(ModeloVehiculo::class, 'id_modelo_vehiculo', 'id_modelo_vehiculo');
    }

    public function reglasGeneraciones(): HasMany
    {
        return $this->hasMany(ReglaGeneracion::class, 'id_generacion', 'id_generacion');
    }
}
