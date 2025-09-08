<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'tw_empleados';
    protected $primaryKey = 'id_empleado';
    protected $fillable = [
            's_nombre',
            's_apellido_paterno',
            's_apellido_materno',
            's_foto_empleado',
            's_rfc',
            's_curp',
            's_correo',
            's_direccion',
            's_num_licencia',
            's_num_seguro',
            's_qr_empleado',
            's_telefono',
            's_contacto_emergencia',
            's_telefono_contacto_emergencia',
            'd_fecha_nacimiento',
            'd_fecha_ingreso',
            'id_tipo_empleado',
            'id_profesion',
            'id_grado_estudios',
            'id_sucursal',
            'id_estado_disponibilidad',
            'id_sexo',
            'id_registro_rh',
            'b_es_usuario',
            'b_activo',
            's_comodin',
    ];
}
