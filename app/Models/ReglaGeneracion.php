<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaGeneracion extends Model
{
    use HasFactory;

    protected $table = 'tr_reglas_generaciones';
    protected $primaryKey = 'id_regla_generacion';

    protected $fillable = [
        'id_regla',
        'id_generacion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function generacion(): BelongsTo
    {
        return $this->belongsTo(Generacion::class, 'id_generacion', 'id_generacion');
    }

    public function regla(): BelongsTo
    {
        return $this->belongsTo(ReglaCompatibilidad::class, 'id_regla', 'id_regla');
    }
}
