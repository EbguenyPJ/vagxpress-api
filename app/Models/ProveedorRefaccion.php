<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorRefaccion extends Model
{
    use HasFactory;

    protected $table = 'tr_proveedores_refaccions';
    protected $primaryKey = 'id_proveedor_refaccion';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'id_refaccion',
        'n_ultimo_costo',
        'd_fecha_ultima_compra',
        's_sku_proveedor',
        's_no_parte_proveedor',
        's_codigo_qr_proveedor',
        'id_usuario_crea',
        'id_usuario_edita',
        'b_activo'
    ];
}
