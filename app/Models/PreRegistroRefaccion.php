<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreRegistroRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tw_pre_registro_refacciones';
    protected $primaryKey = 'id_pre_registro_refaccion';

    public $timestamps = false;

    protected $fillable = [
        's_nombre_refaccion',
        's_numero_parte',
        'id_marca_refaccion',
        'id_categoria_refaccion',
        'id_subcategoria_refaccion',
        'id_clase_refaccion',
        'n_precio_compra',
        'id_usuario_crea',
        'b_activo',
    ];

    protected $casts = [
        'n_precio_compra' => 'decimal:2',
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

    public function claseRefaccion(): BelongsTo
    {
        return $this->belongsTo(ClaseRefaccion::class, 'id_clase_refaccion', 'id_clase_refaccion');
    }

    public function marcaRefaccion(): BelongsTo
    {
        return $this->belongsTo(MarcaRefaccion::class, 'id_marca_refaccion', 'id_marca_refaccion');
    }

    public function subcategoriaRefaccion(): BelongsTo
    {
        return $this->belongsTo(SubcategoriaRefaccion::class, 'id_subcategoria_refaccion', 'id_subcategoria_refaccion');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function entradasEmbarque(): HasMany
    {
        return $this->hasMany(EntradaEmbarque::class, 'id_pre_registro_refaccion', 'id_pre_registro_refaccion');
    }
}
