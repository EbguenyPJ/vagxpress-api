<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidenciaEmbarque extends Model
{
    use HasFactory;
    protected $table = 'tw_evidencias_embarque';
    protected $primaryKey = 'id_evidencia_embarque';
    public $timestamps = false;
    protected $fillable = [
            'id_embarque',
            'id_tipo_evidencia',
            's_evidencia_embarque',
            'd_fecha_creacion',
            'b_activo'
    ];
}
