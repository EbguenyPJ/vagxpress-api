<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        's_nombre_completo',
        's_token',
        'id_empleado',
        'id_tipo_usuario',
        'b_usuario_web',
        'b_usuario_movil',
        'b_activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'b_usuario_web' => 'boolean',
        'b_usuario_movil' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function tipoUsuario(): BelongsTo
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function modulos(): BelongsToMany
    {
        return $this->belongsToMany(Modulo::class, 'tr_modulos_usuarios', 'id_usuario', 'id_modulo')
            ->withPivot('b_activo')
            ->withTimestamps();
    }
}
