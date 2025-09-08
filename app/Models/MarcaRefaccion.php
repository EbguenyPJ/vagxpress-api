<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaRefaccion extends Model
{
    use HasFactory;

    protected $table = "tc_marcas_refacciones";
    protected $primaryKey = "id_marca_refaccion";
    protected $fillable = [
        's_marca_refaccion',
        'b_activo',
    ];
}
