<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'tw_sucursales';
    protected $primaryKey = 'id_sucursal';

    public $timestamps = false;

    protected $fillable = [
        's_sucursal',
        's_razon_social',
        's_representante_legal',
        's_rfc',
        'n_telefono',
        's_correo',
        's_latitud',
        's_longitud',
        's_direccion',
        's_colonia',
        's_codigo_postal',
        's_logo',
        's_firma',
        'id_estado_republica',
        'id_municipio',
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

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_sucursal', 'id_sucursal');
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'id_sucursal', 'id_sucursal');
    }
}
