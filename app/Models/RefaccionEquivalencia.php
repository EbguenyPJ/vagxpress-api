<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefaccionEquivalencia extends Model
{
    use HasFactory;

    protected $table = 'tr_refacciones_equivalencias';
    protected $primaryKey = 'id_refaccion_equivalencia';

    protected $fillable = [
        'id_refaccion',
        'id_equivalencia',
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

    public function equivalencia(): BelongsTo
    {
        return $this->belongsTo(Equivalencia::class, 'id_equivalencia', 'id_equivalencia');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioEdita(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_edita', 'id');
    }
}
