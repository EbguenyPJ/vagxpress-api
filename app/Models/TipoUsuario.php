<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_usuarios';
    protected $primaryKey = 'id_tipo_usuario';

    public const SUPER_ADMIN = 1;
    public const ADMIN = 2;

    protected $fillable = [
        's_tipo_usuario',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }
}
