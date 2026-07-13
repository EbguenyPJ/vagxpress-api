<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_refacciones';
    protected $primaryKey = 'id_estatus_refaccion';

    protected $fillable = [
        's_estatus_refaccion',
        's_color_estatus_refaccion',
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
        return $this->hasMany(Refaccion::class, 'id_estatus_refaccion', 'id_estatus_refaccion');
    }
}
