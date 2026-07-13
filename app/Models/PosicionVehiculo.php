<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosicionVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tc_posiciones_vehiculo';
    protected $primaryKey = 'id_posicion_vehiculo';

    protected $fillable = [
        's_posicion_vehiculo',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function refacciones(): HasMany
    {
        return $this->hasMany(Refaccion::class, 'id_posicion_vehiculo', 'id_posicion_vehiculo');
    }
}
