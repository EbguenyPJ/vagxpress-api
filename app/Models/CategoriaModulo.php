<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaModulo extends Model
{
    use HasFactory;

    protected $table = 'tc_categorias_modulos';
    protected $primaryKey = 'id_categoria_modulo';

    protected $fillable = [
        's_categoria_modulo',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class, 'id_categoria_modulo', 'id_categoria_modulo');
    }
}
