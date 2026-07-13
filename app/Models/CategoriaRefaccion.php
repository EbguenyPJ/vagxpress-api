<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_categorias_refacciones';
    protected $primaryKey = 'id_categoria_refaccion';

    protected $fillable = [
        's_categoria_refaccion',
        's_img_categoria_refaccion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function refacciones(): HasMany
    {
        return $this->hasMany(Refaccion::class, 'id_categoria_refaccion', 'id_categoria_refaccion');
    }

    public function preRegistroRefacciones(): HasMany
    {
        return $this->hasMany(PreRegistroRefaccion::class, 'id_categoria_refaccion', 'id_categoria_refaccion');
    }

    public function subcategoriasRefacciones(): HasMany
    {
        return $this->hasMany(SubcategoriaRefaccion::class, 'id_categoria_refaccion', 'id_categoria_refaccion');
    }
}
