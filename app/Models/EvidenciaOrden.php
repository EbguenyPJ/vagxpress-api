<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidenciaOrden extends Model
{
    use HasFactory;
    protected $table = 'tw_evidencias_orden';
    protected $primaryKey = 'id_evidencia_orden';
    public $timestamps = false;
    protected $fillable = [
            'id_orden',
            's_evidencia_orden',
            'id_tipo_evidencia',
            'b_activo'
    ];
}
