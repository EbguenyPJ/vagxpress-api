<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    use HasFactory;

    protected $table = 'tw_prioridades';
    protected $primaryKey = 'id_prioridad';

    public $timestamps = false;

    protected $fillable = [
        's_prioridad',
        'b_activo'
    ];
}
