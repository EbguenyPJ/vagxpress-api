<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefaccionEquivalencia extends Model
{
    use HasFactory;

    protected $table = 'tr_refacciones_equivalencias';
    protected $primaryKey = 'id_refaccion_equivalencia';
    protected $fillable = [
        'id_refaccion',
        'id_equivalencia',
        'id_usuario_crea',
        'id_usuario_edita',
        'b_activo',
    ];
}
