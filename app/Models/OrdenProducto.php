<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenProducto extends Model
{
    use HasFactory;

    protected $table = 'tr_ordenes_productos';
    protected $primaryKey = 'id_orden_producto';

    public $timestamps = false;

    protected $fillable = [
        'id_orden',
        's_producto',
        'n_cantidad',
        's_comentario',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }
}
