<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusRequisicion extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_requisiciones';
    protected $primaryKey = 'id_estatus_requisicion';

    public $timestamps = false;

    public const PENDIENTE = 1;
    public const AUTORIZADA = 2;
    public const EN_ORDEN_COMPRA = 3;
    public const RECHAZADA = 4;

    protected $fillable = [
        's_estatus_requisicion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function requisiciones(): HasMany
    {
        return $this->hasMany(Requisicion::class, 'id_estatus_requisicion', 'id_estatus_requisicion');
    }

    public function requisicionesRefacciones(): HasMany
    {
        return $this->hasMany(RequisicionRefaccion::class, 'id_estatus_requisicion', 'id_estatus_requisicion');
    }
}
