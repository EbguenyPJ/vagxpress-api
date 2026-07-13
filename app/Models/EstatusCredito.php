<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusCredito extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_creditos';
    protected $primaryKey = 'id_estatus_credito';

    public $timestamps = false;

    public const ACTIVO = 1;
    public const PAGADO = 2;
    public const VENCIDO = 3;

    protected $fillable = [
        's_estatus_credito',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function creditos(): HasMany
    {
        return $this->hasMany(Credito::class, 'id_estatus_credito', 'id_estatus_credito');
    }
}
