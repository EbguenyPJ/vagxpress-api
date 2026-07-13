<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaMarca extends Model
{
    use HasFactory;

    protected $table = 'tr_reglas_marcas';
    protected $primaryKey = 'id_regla_marca';

    protected $fillable = [
        'id_regla',
        'id_marca_vehiculo',
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

    public function regla(): BelongsTo
    {
        return $this->belongsTo(ReglaCompatibilidad::class, 'id_regla', 'id_regla');
    }
}
