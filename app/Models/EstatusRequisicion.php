<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusRequisicion extends Model
{
    use HasFactory;

    protected $table = 'tc_estatus_requisiciones';
    protected $primaryKey = 'id_estatus_requisicion';

    public $timestamps = false;

    protected $fillable = [
        's_estatus_requisicion',
        'b_activo'
    ];
}
