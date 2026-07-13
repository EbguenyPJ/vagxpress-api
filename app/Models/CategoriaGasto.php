<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaGasto extends Model
{
    use HasFactory;

    protected $table = 'tc_categorias_gastos';
    protected $primaryKey = 'id_categoria_gasto';

    public $timestamps = false;

    protected $fillable = [
        's_categoria_gasto',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function tiposGastos(): HasMany
    {
        return $this->hasMany(TipoGasto::class, 'id_categoria_gasto', 'id_categoria_gasto');
    }
}
