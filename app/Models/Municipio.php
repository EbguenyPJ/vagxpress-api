<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'tc_municipios';
    protected $primaryKey = 'id_municipio';

    public $timestamps = false;

    protected $fillable = [
        's_municipio',
        'id_estado_republica',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estadoRepublica(): BelongsTo
    {
        return $this->belongsTo(EstadoRepublica::class, 'id_estado_republica', 'id_estado_republica');
    }

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class, 'id_municipio', 'id_municipio');
    }
}
