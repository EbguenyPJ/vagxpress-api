<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReglaCompatibilidad extends Model
{
    use HasFactory;

    protected $table = 'tw_reglas_compatibilidad';
    protected $primaryKey = 'id_regla';

    protected $fillable = [
        'id_refaccion',
        's_resumen',
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

    public function reglasGeneraciones(): HasMany
    {
        return $this->hasMany(ReglaGeneracion::class, 'id_regla', 'id_regla');
    }

    public function reglasMarcas(): HasMany
    {
        return $this->hasMany(ReglaMarca::class, 'id_regla', 'id_regla');
    }

    public function reglasModelos(): HasMany
    {
        return $this->hasMany(ReglaModelo::class, 'id_regla', 'id_regla');
    }

    public function reglasMotores(): HasMany
    {
        return $this->hasMany(ReglaMotor::class, 'id_regla', 'id_regla');
    }
}
