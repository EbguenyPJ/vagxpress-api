<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'tc_configuraciones';
    protected $primaryKey = 'id_configuracion';
    protected $fillable = [
        's_configuracion',
        'b_activo',
    ];
}
