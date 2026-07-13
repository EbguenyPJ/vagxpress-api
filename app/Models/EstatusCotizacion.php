<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusCotizacion extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_cotizaciones';
    protected $primaryKey = 'id_estatus_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        's_estatus_cotizacion',
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
        return $this->hasMany(Cotizacion::class, 'id_estatus_cotizacion', 'id_estatus_cotizacion');
    }
}
