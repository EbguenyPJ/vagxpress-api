<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equivalencia extends Model
{
    use HasFactory;

    protected $table = 'tw_equivalencias';
    protected $primaryKey = 'id_equivalencia';

    protected $fillable = [
        's_nombre_equivalencia',
        's_descripcion_equivalencia',
        'id_usuario_crea',
        'id_usuario_edita',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioEdita(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_edita', 'id');
    }

    public function refaccionesEquivalencias(): HasMany
    {
        return $this->hasMany(RefaccionEquivalencia::class, 'id_equivalencia', 'id_equivalencia');
    }
}
