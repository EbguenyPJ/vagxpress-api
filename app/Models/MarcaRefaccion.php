<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarcaRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_marcas_refacciones';
    protected $primaryKey = 'id_marca_refaccion';

    protected $fillable = [
        's_marca_refaccion',
        's_img_marca_refaccion',
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
        return $this->hasMany(Refaccion::class, 'id_marca_refaccion', 'id_marca_refaccion');
    }

    public function preRegistroRefacciones(): HasMany
    {
        return $this->hasMany(PreRegistroRefaccion::class, 'id_marca_refaccion', 'id_marca_refaccion');
    }
}
