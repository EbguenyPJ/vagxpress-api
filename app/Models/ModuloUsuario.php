<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloUsuario extends Model
{
    use HasFactory;

    protected $table = 'tr_modulos_usuarios';
    protected $primaryKey = 'id_modulo_usuario';
    protected $fillable = [
        'id_modulo',
        'id_usuario',
        'b_activo'
    ];
}
