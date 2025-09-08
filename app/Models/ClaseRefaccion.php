<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaseRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tc_clases_refacciones';
    protected $primaryKey = 'id_clase_refaccion';
    protected $fillable = [
        's_clase_refaccion',
        'b_activo',
    ];
}
