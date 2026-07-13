<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoRuta extends Model
{
    use HasFactory;

    protected $table = 'tc_tipo_ruta';
    protected $primaryKey = 'id_tipo_ruta';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_ruta',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function puntosRuta(): HasMany
    {
        return $this->hasMany(PuntoRuta::class, 'id_tipo_ruta', 'id_tipo_ruta');
    }
}
