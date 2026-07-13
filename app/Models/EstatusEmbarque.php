<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusEmbarque extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_embarque';
    protected $primaryKey = 'id_estatus_embarque';

    public $timestamps = false;

    public const PENDIENTE = 1;
    public const APROBADO = 2;
    public const RECHAZADO = 3;

    protected $fillable = [
        's_estatus_embarque',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function embarques(): HasMany
    {
        return $this->hasMany(Embarque::class, 'id_estatus_embarque', 'id_estatus_embarque');
    }
}
