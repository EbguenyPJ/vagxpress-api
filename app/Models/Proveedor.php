<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'tw_proveedores';
    protected $primaryKey = 'id_proveedor';
    protected $fillable = [
        's_proveedor',
        's_nombre_contacto',
        's_telefono',
        's_rfc',
        's_img_proveedor',
        'b_activo',
    ];
}
