<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoRequisicion extends Model
{
    use HasFactory;

    protected $table = 'tc_tipos_requisiciones';
    protected $primaryKey = 'id_tipo_requisicion';

    public $timestamps = false;

    public const AUTOMATICA = 1;
    public const MANUAL = 2;

    protected $fillable = [
        's_tipo_requisicion',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function requisiciones(): HasMany
    {
        return $this->hasMany(Requisicion::class, 'id_tipo_requisicion', 'id_tipo_requisicion');
    }
}
