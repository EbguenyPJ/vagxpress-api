<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCredito extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_creditos';
    protected $primaryKey = 'id_tipo_credito';

    public $timestamps = false;

    public const CLIENTE = 1;

    protected $fillable = [
        's_tipo_credito',
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
        return $this->hasMany(Credito::class, 'id_tipo_credito', 'id_tipo_credito');
    }
}
