<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
    use HasFactory;

    protected $table = 'tw_destinos';
    protected $primaryKey = 'id_destino';

    public $timestamps = false;

    protected $fillable = [
        's_nombre_destino',
        's_direccion',
        's_referencia_destino',
        'id_tipo_destino',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tipoDestino(): BelongsTo
    {
        return $this->belongsTo(TipoDestino::class, 'id_tipo_destino', 'id_tipo_destino');
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class, 'id_destino', 'id_destino');
    }
}
