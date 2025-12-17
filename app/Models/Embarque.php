<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embarque extends Model
{
    use HasFactory;
    protected $table = 'tw_embarques';
    protected $primaryKey = 'id_embarque';
    public $timestamps = false;
    protected $fillable = [
            'id_proveedor',
            'd_fecha_creacion',
            'id_usuario_crea',
            'id_estatus_embarque',
            'b_activo'
    ];
}
