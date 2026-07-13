<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prioridad extends Model
{
    use HasFactory;

    protected $table = 'tc_prioridades';
    protected $primaryKey = 'id_prioridad';

    public $timestamps = false;

    protected $fillable = [
        's_prioridad',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function requisicionesRefacciones(): HasMany
    {
        return $this->hasMany(RequisicionRefaccion::class, 'id_prioridad', 'id_prioridad');
    }
}
