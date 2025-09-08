<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provedor extends Model
{
    use HasFactory;

    protected $table = 'tw_provedores';
    protected $primaryKey = 'id_provedor';
    protected $fillable = [
        's_provedor',
        's_nombre_contacto',
        's_telefono',
        's_rfc',
        'b_activo',
    ];
}
