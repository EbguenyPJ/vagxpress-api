<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'tc_bancos';
    protected $primaryKey = 'id_banco';

    public $timestamps = false;

    protected $fillable = [
        's_banco',
        's_img_banco',
        'b_activo'
    ];
}
