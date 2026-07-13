<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesion extends Model
{
    use HasFactory;

    protected $table = 'tc_profesiones';
    protected $primaryKey = 'id_profesion';

    public $timestamps = false;

    protected $fillable = [
        's_profesion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_profesion', 'id_profesion');
    }
}
