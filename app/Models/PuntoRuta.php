<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuntoRuta extends Model
{
    use HasFactory;

    protected $table = 'tw_puntos_ruta';
    protected $primaryKey = 'id_punto_ruta';

    public $timestamps = false;

    protected $fillable = [
        'id_orden',
        'id_tipo_ruta',
        'n_latitud',
        'n_longitud',
        'timestamp',
        'b_activo',
    ];

    protected $casts = [
        'n_latitud' => 'decimal:7',
        'n_longitud' => 'decimal:7',
        'timestamp' => 'datetime',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }

    public function tipoRuta(): BelongsTo
    {
        return $this->belongsTo(TipoRuta::class, 'id_tipo_ruta', 'id_tipo_ruta');
    }
}
