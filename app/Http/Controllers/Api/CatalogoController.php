<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\CategoriaRefaccion;
use App\Models\ClaseRefaccion;
use App\Models\CuentaBancaria;
use App\Models\EstatusRefaccion;
use App\Models\GradoEstudios;
use App\Models\MarcaRefaccion;
use App\Models\MetodoPago;
use App\Models\PorcentajeUtilidad;
use App\Models\PosicionVehiculo;
use App\Models\Profesion;
use App\Models\SubcategoriaRefaccion;
use App\Models\Sucursal;
use App\Models\TipoCliente;
use App\Models\TipoCotizacion;
use App\Models\TipoCredito;
use App\Models\TipoEmpleado;
use App\Models\TipoUsuario;
use App\Models\UbicacionAlmacen;
use App\Models\UnidadMedida;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Catálogos de solo lectura del sistema.
 *
 * GET /api/catalogos/{catalogo} — la lista de slugs válidos está en el mapa;
 * los catálogos con forma especial tienen su propio método.
 */
class CatalogoController extends Controller
{
    /** @var array<string, class-string> catálogos planos: slug => modelo */
    private const CATALOGOS = [
        'marcas-refacciones' => MarcaRefaccion::class,
        'categorias-refacciones' => CategoriaRefaccion::class,
        'subcategorias-refacciones' => SubcategoriaRefaccion::class,
        'clases-refacciones' => ClaseRefaccion::class,
        'estatus-refacciones' => EstatusRefaccion::class,
        'unidades-medida' => UnidadMedida::class,
        'posiciones-vehiculo' => PosicionVehiculo::class,
        'ubicaciones-almacen' => UbicacionAlmacen::class,
        'metodos-pago' => MetodoPago::class,
        'bancos' => Banco::class,
        'tipos-creditos' => TipoCredito::class,
        'tipos-cliente' => TipoCliente::class,
        'tipos-empleados' => TipoEmpleado::class,
        'tipos-usuarios' => TipoUsuario::class,
        'tipos-cotizaciones' => TipoCotizacion::class,
        'profesiones' => Profesion::class,
        'grados-estudios' => GradoEstudios::class,
    ];

    public function show(string $catalogo): JsonResponse
    {
        if (method_exists($this, $metodo = 'catalogo' . str_replace('-', '', ucwords($catalogo, '-')))) {
            return ApiResponse::ok($this->{$metodo}(), 'Catálogo obtenido correctamente');
        }

        $modelo = self::CATALOGOS[$catalogo] ?? throw new NotFoundHttpException("Catálogo '$catalogo' no existe");

        return ApiResponse::ok($modelo::activo()->get(), 'Catálogo obtenido correctamente');
    }

    /** Solo porcentajes de utilidad configurables desde el POS (tipos 2 y 3). */
    private function catalogoPorcentajesUtilidad()
    {
        return PorcentajeUtilidad::activo()
            ->whereIn('id_tipo_configuracion', [2, 3])
            ->get(['id_porcentaje_utilidad', 'n_porcentaje_utilidad', 'id_tipo_configuracion']);
    }

    /** Cuentas bancarias con la imagen del banco para el selector de pago. */
    private function catalogoCuentasBancarias()
    {
        return CuentaBancaria::activo()
            ->with('banco')
            ->get()
            ->map(fn (CuentaBancaria $cuenta) => [
                'id_cuenta_bancaria' => $cuenta->id_cuenta_bancaria,
                's_nombre_cuenta' => $cuenta->s_nombre_cuenta,
                'id_metodo_pago' => $cuenta->id_metodo_pago,
                'id_banco' => $cuenta->id_banco,
                's_img_banco' => $cuenta->banco?->s_img_banco,
            ]);
    }

    /** Sucursales con el nombre de estado y municipio. */
    private function catalogoSucursales()
    {
        return Sucursal::activo()
            ->with(['estadoRepublica', 'municipio'])
            ->get()
            ->map(fn (Sucursal $sucursal) => array_merge($sucursal->toArray(), [
                's_estado_republica' => $sucursal->estadoRepublica?->s_estado_republica,
                's_municipio' => $sucursal->municipio?->s_municipio,
            ]));
    }
}
