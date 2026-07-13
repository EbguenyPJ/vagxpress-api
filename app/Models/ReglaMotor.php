<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaMotor extends Model
{
    use HasFactory;

    protected $table = 'tr_reglas_motores';
    protected $primaryKey = 'id_regla_motor';

    protected $fillable = [
        'id_regla',
        'id_motor',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class, 'id_motor', 'id_motor');
    }

    public function regla(): BelongsTo
    {
        return $this->belongsTo(ReglaCompatibilidad::class, 'id_regla', 'id_regla');
    }
}
