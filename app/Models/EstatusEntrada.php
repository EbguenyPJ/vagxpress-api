<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusEntrada extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_entrada';
    protected $primaryKey = 'id_estatus_entrada';

    public $timestamps = false;

    public const PENDIENTE = 1;
    public const APROBADA = 2;
    public const RECHAZADA = 3;

    protected $fillable = [
        's_estatus_entrada',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function entradasEmbarque(): HasMany
    {
        return $this->hasMany(EntradaEmbarque::class, 'id_estatus_entrada', 'id_estatus_entrada');
    }
}
