<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpleado extends Model
{
    use HasFactory;

    protected $table = "tc_tipos_empleados";
    protected $primaryKey = 'id_tipo_empleado';
    protected $fillable = [
        's_tipo_empleado',
        'b_activo',
    ];
}
