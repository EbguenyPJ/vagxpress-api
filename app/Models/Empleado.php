<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'tw_empleados';
    protected $primaryKey = 'id_empleado';

    /** id_tipo_empleado del catálogo: repartidores de la app móvil. */
    public const TIPO_REPARTIDOR = 2;

    protected $fillable = [
        's_nombre',
        's_apellido_paterno',
        's_apellido_materno',
        's_foto_empleado',
        's_rfc',
        's_curp',
        's_correo',
        's_direccion',
        's_num_licencia',
        's_num_seguro',
        's_qr_empleado',
        's_telefono',
        's_contacto_emergencia',
        's_telefono_contacto_emergencia',
        'd_fecha_nacimiento',
        'd_fecha_ingreso',
        'id_tipo_empleado',
        'id_profesion',
        'id_grado_estudios',
        'id_sucursal',
        'id_estado_disponibilidad',
        'id_sexo',
        'id_registro_rh',
        'b_es_usuario',
        'b_activo',
        's_comodin',
    ];

    protected $casts = [
        'd_fecha_nacimiento' => 'date',
        'd_fecha_ingreso' => 'date',
        'b_es_usuario' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estadoDisponibilidad(): BelongsTo
    {
        return $this->belongsTo(EstadoDisponibilidad::class, 'id_estado_disponibilidad', 'id_estado_disponibilidad');
    }

    public function gradoEstudios(): BelongsTo
    {
        return $this->belongsTo(GradoEstudios::class, 'id_grado_estudios', 'id_grado_estudios');
    }

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function tipoEmpleado(): BelongsTo
    {
        return $this->belongsTo(TipoEmpleado::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class, 'id_repartidor', 'id_empleado');
    }

    public function habilidadesEmpleados(): HasMany
    {
        return $this->hasMany(HabilidadEmpleado::class, 'id_empleado', 'id_empleado');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_empleado', 'id_empleado');
    }
}
