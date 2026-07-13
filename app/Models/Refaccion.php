<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refaccion extends Model
{
    use HasFactory;

    protected $table = 'tw_refacciones';
    protected $primaryKey = 'id_refaccion';

    protected $fillable = [
        's_nombre_refaccion',
        's_descripcion',
        's_observaciones',
        's_numero_parte',
        's_codigo_interno',
        's_codigo_alterno',
        's_sku',
        's_codigo_aces',
        's_imagen_refaccion',
        's_codigo_qr',
        'n_precio_compra',
        'n_precio_venta',
        'n_costo_promedio',
        'n_precio_mayoreo',
        'n_precio_minimo_autorizado',
        'n_stock_actual',
        'n_stock_minimo',
        'n_stock_maximo',
        'n_tiempo_reposicion',
        'id_marca_refaccion',
        'id_unidad_medida',
        'id_proveedor',
        'id_clase_refaccion',
        'id_categoria_refaccion',
        'id_subcategoria_refaccion',
        'id_posicion_vehiculo',
        'id_ubicacion_almacen',
        'id_codigo_sat',
        'id_estatus_refaccion',
        'id_codigo_aces',
        'b_importado',
        'b_activo',
        'id_usuario_crea',
        'id_usuario_edita',
    ];

    protected $casts = [
        'n_precio_compra' => 'decimal:2',
        'n_precio_venta' => 'decimal:2',
        'n_costo_promedio' => 'decimal:2',
        'n_precio_mayoreo' => 'decimal:2',
        'n_precio_minimo_autorizado' => 'decimal:2',
        'b_importado' => 'boolean',
        'b_activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('b_activo', 1);
    }

    public function categoriaRefaccion(): BelongsTo
    {
        return $this->belongsTo(CategoriaRefaccion::class, 'id_categoria_refaccion', 'id_categoria_refaccion');
    }

    public function claseRefaccion(): BelongsTo
    {
        return $this->belongsTo(ClaseRefaccion::class, 'id_clase_refaccion', 'id_clase_refaccion');
    }

    public function estatusRefaccion(): BelongsTo
    {
        return $this->belongsTo(EstatusRefaccion::class, 'id_estatus_refaccion', 'id_estatus_refaccion');
    }

    public function marcaRefaccion(): BelongsTo
    {
        return $this->belongsTo(MarcaRefaccion::class, 'id_marca_refaccion', 'id_marca_refaccion');
    }

    public function posicionVehiculo(): BelongsTo
    {
        return $this->belongsTo(PosicionVehiculo::class, 'id_posicion_vehiculo', 'id_posicion_vehiculo');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function subcategoriaRefaccion(): BelongsTo
    {
        return $this->belongsTo(SubcategoriaRefaccion::class, 'id_subcategoria_refaccion', 'id_subcategoria_refaccion');
    }

    public function ubicacionAlmacen(): BelongsTo
    {
        return $this->belongsTo(UbicacionAlmacen::class, 'id_ubicacion_almacen', 'id_ubicacion_almacen');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida', 'id_unidad_medida');
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_crea', 'id');
    }

    public function usuarioEdita(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_edita', 'id');
    }

    public function reglasCompatibilidad(): HasMany
    {
        return $this->hasMany(ReglaCompatibilidad::class, 'id_refaccion', 'id_refaccion');
    }

    public function entradasEmbarque(): HasMany
    {
        return $this->hasMany(EntradaEmbarque::class, 'id_refaccion', 'id_refaccion');
    }

    public function proveedoresRefacciones(): HasMany
    {
        return $this->hasMany(ProveedorRefaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function ventasRefacciones(): HasMany
    {
        return $this->hasMany(VentaRefaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function refaccionesEquivalencias(): HasMany
    {
        return $this->hasMany(RefaccionEquivalencia::class, 'id_refaccion', 'id_refaccion');
    }

    public function cotizacionesRefacciones(): HasMany
    {
        return $this->hasMany(CotizacionRefaccion::class, 'id_refaccion', 'id_refaccion');
    }

    public function requisicionesRefacciones(): HasMany
    {
        return $this->hasMany(RequisicionRefaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
