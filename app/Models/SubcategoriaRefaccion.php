<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubcategoriaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_subcategorias_refacciones';
    protected $primaryKey = 'id_subcategoria_refaccion';

    protected $fillable = [
        'id_categoria_refaccion',
        's_subcategoria_refaccion',
        's_img_subcategoria_refaccion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function categoriaRefaccion(): BelongsTo
    {
        return $this->belongsTo(CategoriaRefaccion::class, 'id_categoria_refaccion', 'id_categoria_refaccion');
    }

    public function refacciones(): HasMany
    {
        return $this->hasMany(Refaccion::class, 'id_subcategoria_refaccion', 'id_subcategoria_refaccion');
    }

    public function preRegistroRefacciones(): HasMany
    {
        return $this->hasMany(PreRegistroRefaccion::class, 'id_subcategoria_refaccion', 'id_subcategoria_refaccion');
    }
}
