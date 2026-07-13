<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Version extends Model
{
    use HasFactory;

    protected $table = 'tw_versiones';
    protected $primaryKey = 'id_version';

    protected $fillable = [
        'id_usuario',
        's_nombre_version',
        's_descripcion_version',
        'd_fecha_actualizacion_version',
        'b_activo',
    ];

    protected $casts = [
        'd_fecha_actualizacion_version' => 'date',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
