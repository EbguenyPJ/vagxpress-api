<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisicion extends Model
{
    use HasFactory;

    protected $table = 'tw_requisiciones';
    protected $primaryKey = 'id_requisicion';

    protected $fillable = [
        's_observacion',
        'n_cantidad_refacciones',
        'n_total_estimado',
        'd_fecha_limite',
        'd_fecha_solicitud',
        'id_estatus_requisicion',
        'id_tipo_requisicion',
        'id_usuario_crea',
        'id_usuario_modifica',
        'id_usuario_autoriza',
        'b_activo',
    ];

    protected $casts = [
        'n_total_estimado' => 'decimal:2',
        'd_fecha_limite' => 'date',
        'd_fecha_solicitud' => 'date',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estatusRequisicion(): BelongsTo
    {
        return $this->belongsTo(EstatusRequisicion::class, 'id_estatus_requisicion', 'id_estatus_requisicion');
    }

    public function tipoRequisicion(): BelongsTo
    {
        return $this->belongsTo(TipoRequisicion::class, 'id_tipo_requisicion', 'id_tipo_requisicion');
    }

    public function usuarioAutoriza(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_autoriza', 'id');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioModifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_modifica', 'id');
    }

    public function ordenesCompras(): HasMany
    {
        return $this->hasMany(OrdenCompra::class, 'id_requisicion', 'id_requisicion');
    }

    public function requisicionesRefacciones(): HasMany
    {
        return $this->hasMany(RequisicionRefaccion::class, 'id_requisicion', 'id_requisicion');
    }
}
