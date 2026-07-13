<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoGasto extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_gastos';
    protected $primaryKey = 'id_tipo_gasto';

    protected $fillable = [
        'id_categoria_gasto',
        's_tipo_gasto',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function categoriaGasto(): BelongsTo
    {
        return $this->belongsTo(CategoriaGasto::class, 'id_categoria_gasto', 'id_categoria_gasto');
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'id_tipo_gasto', 'id_tipo_gasto');
    }
}
