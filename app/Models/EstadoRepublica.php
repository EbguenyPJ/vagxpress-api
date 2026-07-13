<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoRepublica extends Model
{
    use HasFactory;

    protected $table = 'tc_estados_republica';
    protected $primaryKey = 'id_estado_republica';

    public $timestamps = false;

    protected $fillable = [
        's_estado_republica',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'id_estado_republica', 'id_estado_republica');
    }

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class, 'id_estado_republica', 'id_estado_republica');
    }
}
