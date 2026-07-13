<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'tw_proveedores';
    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        's_proveedor',
        's_nombre_contacto',
        's_telefono',
        's_rfc',
        's_img_proveedor',
        'b_activo',
    ];

    protected $casts = [
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function refacciones(): HasMany
    {
        return $this->hasMany(Refaccion::class, 'id_proveedor', 'id_proveedor');
    }

    public function proveedoresRefacciones(): HasMany
    {
        return $this->hasMany(ProveedorRefaccion::class, 'id_proveedor', 'id_proveedor');
    }

    public function ordenesCompras(): HasMany
    {
        return $this->hasMany(OrdenCompra::class, 'id_proveedor', 'id_proveedor');
    }

    public function embarques(): HasMany
    {
        return $this->hasMany(Embarque::class, 'id_proveedor', 'id_proveedor');
    }
}
