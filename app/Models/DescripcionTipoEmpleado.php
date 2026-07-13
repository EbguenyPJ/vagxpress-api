<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescripcionTipoEmpleado extends Model
{
    use HasFactory;

    protected $table = 'tc_descripciones_tipos_empleados';
    protected $primaryKey = 'id_descripcion_tipo_empleado';

    public $timestamps = false;

    protected $fillable = [
        'id_tipo_empleado',
        's_descripcion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tipoEmpleado(): BelongsTo
    {
        return $this->belongsTo(TipoEmpleado::class, 'id_tipo_empleado', 'id_tipo_empleado');
    }
}
