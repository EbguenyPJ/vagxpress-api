<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UbicacionAlmacen extends Model
{
    use HasFactory;

    protected $table = 'tc_ubicaciones_almacen';
    protected $primaryKey = 'id_ubicacion_almacen';

    protected $fillable = [
        's_ubicacion_almacen',
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
        return $this->hasMany(Refaccion::class, 'id_ubicacion_almacen', 'id_ubicacion_almacen');
    }
}
