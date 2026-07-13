<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCotizacion extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_cotizaciones';
    protected $primaryKey = 'id_tipo_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_cotizacion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_tipo_cotizacion', 'id_tipo_cotizacion');
    }
}
