<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_refacciones';
    protected $primaryKey = 'id_estatus_refaccion';
    protected $fillable = [
        's_estatus_refaccion',
        'b_activo',
    ];
}
