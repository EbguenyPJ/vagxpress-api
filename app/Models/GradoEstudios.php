<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradoEstudios extends Model
{
    use HasFactory;

    protected $table = 'tc_grados_estudios';
    protected $primaryKey = 'id_grado_estudios';

    public $timestamps = false;

    protected $fillable = [
        's_grado_estudios',
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
        return $this->hasMany(Empleado::class, 'id_grado_estudios', 'id_grado_estudios');
    }
}
