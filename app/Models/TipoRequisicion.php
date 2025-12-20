<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRequisicion extends Model
{
    use HasFactory;

    protected $table = 'tw_tipos_requisicion';
    protected $primaryKey = 'id_tipo_requisicion';

    public $timestamps = false;

    protected $fillable = [
        's_tipo_requisicion',
        'b_activo'
    ];
}
