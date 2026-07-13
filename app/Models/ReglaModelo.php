<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaModelo extends Model
{
    use HasFactory;

    protected $table = 'tr_reglas_modelos';
    protected $primaryKey = 'id_regla_modelo';

    protected $fillable = [
        'id_regla',
        'id_modelo_vehiculo',
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

    public function regla(): BelongsTo
    {
        return $this->belongsTo(ReglaCompatibilidad::class, 'id_regla', 'id_regla');
    }
}
