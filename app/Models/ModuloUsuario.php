<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuloUsuario extends Model
{
    use HasFactory;

    protected $table = 'tr_modulos_usuarios';
    protected $primaryKey = 'id_modulo_usuario';

    protected $fillable = [
        'id_modulo',
        'id_usuario',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
