<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'tc_bancos';
    protected $primaryKey = 'id_banco';

    public $timestamps = false;

    protected $fillable = [
        's_banco',
        's_img_banco',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function cuentasBancarias(): HasMany
    {
        return $this->hasMany(CuentaBancaria::class, 'id_banco', 'id_banco');
    }
}
