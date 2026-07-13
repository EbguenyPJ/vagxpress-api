<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotivoPedido extends Model
{
    use HasFactory;

    protected $table = 'tc_motivos_pedidos';
    protected $primaryKey = 'id_motivo_pedido';

    public $timestamps = false;

    protected $fillable = [
        's_motivo_pedido',
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
        return $this->hasMany(RequisicionRefaccion::class, 'id_motivo_pedido', 'id_motivo_pedido');
    }
}
