<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'tc_unidades_medida';
    protected $primaryKey = 'id_unidad_medida';

    protected $fillable = [
        's_unidad_medida',
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
        return $this->hasMany(Refaccion::class, 'id_unidad_medida', 'id_unidad_medida');
    }
}
