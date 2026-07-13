<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntradaEmbarque extends Model
{
    use HasFactory;

    protected $table = 'tr_entradas_embarque';
    protected $primaryKey = 'id_entrada_embarque';

    public $timestamps = false;

    protected $fillable = [
        'id_embarque',
        'id_refaccion',
        'id_pre_registro_refaccion',
        'id_estatus_entrada',
        'n_cantidad',
        'n_precio_compra',
        's_codigo_barras',
        'd_fecha_creacion',
        'b_activo',
    ];

    protected $casts = [
        'n_precio_compra' => 'decimal:2',
        'd_fecha_creacion' => 'datetime',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function embarque(): BelongsTo
    {
        return $this->belongsTo(Embarque::class, 'id_embarque', 'id_embarque');
    }

    public function estatusEntrada(): BelongsTo
    {
        return $this->belongsTo(EstatusEntrada::class, 'id_estatus_entrada', 'id_estatus_entrada');
    }

    public function preRegistroRefaccion(): BelongsTo
    {
        return $this->belongsTo(PreRegistroRefaccion::class, 'id_pre_registro_refaccion', 'id_pre_registro_refaccion');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
