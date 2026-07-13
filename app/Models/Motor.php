<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'tc_motores';
    protected $primaryKey = 'id_motor';

    protected $fillable = [
        's_motor',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function reglasMotores(): HasMany
    {
        return $this->hasMany(ReglaMotor::class, 'id_motor', 'id_motor');
    }
}
