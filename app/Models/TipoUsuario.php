<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_usuarios';
    protected $primaryKey = 'id_tipo_usuario';
    protected $fillable = [
        's_tipo_usuario',
        'b_activo',
    ];
}
