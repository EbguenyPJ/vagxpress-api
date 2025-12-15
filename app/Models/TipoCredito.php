<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCredito extends Model
{
    use HasFactory;

    protected $table = "tc_tipos_creditos";
    protected $primaryKey = 'id_tipo_credito';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_credito',
        'b_activo',
    ];
}
