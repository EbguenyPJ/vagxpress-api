<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Embarque extends Model
{
    use HasFactory;

    protected $table = 'tw_embarques';
    protected $primaryKey = 'id_embarque';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'd_fecha_creacion',
        'id_usuario_crea',
        'id_estatus_embarque',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_creacion' => 'datetime',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function estatusEmbarque(): BelongsTo
    {
        return $this->belongsTo(EstatusEmbarque::class, 'id_estatus_embarque', 'id_estatus_embarque');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function evidenciasEmbarque(): HasMany
    {
        return $this->hasMany(EvidenciaEmbarque::class, 'id_embarque', 'id_embarque');
    }

    public function entradasEmbarque(): HasMany
    {
        return $this->hasMany(EntradaEmbarque::class, 'id_embarque', 'id_embarque');
    }
}
