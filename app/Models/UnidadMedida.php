<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'tc_unidades_medida';
    protected $primaryKey = 'id_unidad_medida';
    protected $fillable = [
        's_unidad_medida',
        'b_activo',
    ];
}
