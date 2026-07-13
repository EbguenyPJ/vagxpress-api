<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDestino extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_destinos';
    protected $primaryKey = 'id_tipo_destino';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_destino',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function destinos(): HasMany
    {
        return $this->hasMany(Destino::class, 'id_tipo_destino', 'id_tipo_destino');
    }
}
