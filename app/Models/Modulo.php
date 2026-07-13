<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'tc_modulos';
    protected $primaryKey = 'id_modulo';

    protected $fillable = [
        'id_categoria_modulo',
        's_modulo',
        's_ruta',
        's_icono',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function categoriaModulo(): BelongsTo
    {
        return $this->belongsTo(CategoriaModulo::class, 'id_categoria_modulo', 'id_categoria_modulo');
    }

    public function modulosUsuarios(): HasMany
    {
        return $this->hasMany(ModuloUsuario::class, 'id_modulo', 'id_modulo');
    }

    public function tiposConfiguraciones(): HasMany
    {
        return $this->hasMany(TipoConfiguracion::class, 'id_modulo', 'id_modulo');
    }
}
