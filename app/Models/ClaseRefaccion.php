<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaseRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_clases_refacciones';
    protected $primaryKey = 'id_clase_refaccion';

    protected $fillable = [
        's_clase_refaccion',
        's_color_clase_refaccion',
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
        return $this->hasMany(Refaccion::class, 'id_clase_refaccion', 'id_clase_refaccion');
    }

    public function preRegistroRefacciones(): HasMany
    {
        return $this->hasMany(PreRegistroRefaccion::class, 'id_clase_refaccion', 'id_clase_refaccion');
    }
}
