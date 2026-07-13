<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogoController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CompatibilidadController;
use App\Http\Controllers\Api\CorteController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmbarqueController;
use App\Http\Controllers\Api\GastoController;
use App\Http\Controllers\Api\CotizacionController;
use App\Http\Controllers\Api\RefaccionController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\OrdenCompraController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\RepartoController;
use App\Http\Controllers\Api\RequisicionController;
use App\Http\Controllers\Api\ModuloController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\VersionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API — VagXpress Refaccionaria
|--------------------------------------------------------------------------
| Todas las rutas requieren token Sanctum excepto el login.
| Agrupadas por dominio; los controladores viven en App\Http\Controllers\Api.
*/

// ── Autenticación (público) ─────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/login-movil', [AuthController::class, 'loginMovil']);

Route::middleware('auth:sanctum')->group(function () {
    // ── Sesión ──────────────────────────────────────────────────────────
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // ── Usuarios ────────────────────────────────────────────────────────
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index']);
        Route::post('/', [UsuarioController::class, 'store']);
        Route::get('/{idUsuario}/perfil', [UsuarioController::class, 'perfil']);
        Route::put('/{idUsuario}/accesos', [UsuarioController::class, 'actualizarAccesos']);
        Route::put('/{idUsuario}/estatus', [UsuarioController::class, 'actualizarEstatus']);
        Route::put('/{idUsuario}/tipo-usuario', [UsuarioController::class, 'actualizarTipoUsuario']);
        Route::get('/{idUsuario}/modulos', [ModuloController::class, 'deUsuario']);
        Route::put('/{idUsuario}/modulos', [ModuloController::class, 'sincronizar']);
    });

    // ── Clientes ────────────────────────────────────────────────────────
    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClienteController::class, 'index']);
        Route::get('/selector', [ClienteController::class, 'selector']);
        Route::post('/', [ClienteController::class, 'store']);
        Route::put('/{idCliente}', [ClienteController::class, 'update']);
    });

    // ── Proveedores ─────────────────────────────────────────────────────
    Route::prefix('proveedores')->group(function () {
        Route::get('/', [ProveedorController::class, 'index']);
        Route::post('/', [ProveedorController::class, 'store']);
        Route::put('/{idProveedor}', [ProveedorController::class, 'update']);
    });

    // ── Empleados ───────────────────────────────────────────────────────
    Route::prefix('empleados')->group(function () {
        Route::get('/', [EmpleadoController::class, 'index']);
        Route::get('/sin-usuario', [EmpleadoController::class, 'sinUsuario']);
        Route::get('/sucursal/{idSucursal}', [EmpleadoController::class, 'porSucursal']);
        Route::get('/sucursal/{idSucursal}/gerente', [EmpleadoController::class, 'gerenteDeSucursal']);
        Route::get('/usuario/{idUsuario}', [EmpleadoController::class, 'porUsuario']);
        Route::post('/', [EmpleadoController::class, 'store']);
        Route::put('/{idEmpleado}', [EmpleadoController::class, 'update']);
        Route::get('/{idEmpleado}/habilidades', [EmpleadoController::class, 'habilidades']);
        Route::put('/{idEmpleado}/habilidades', [EmpleadoController::class, 'actualizarHabilidades']);
    });

    // ── Refacciones ─────────────────────────────────────────────────────
    Route::prefix('refacciones')->group(function () {
        Route::get('/', [RefaccionController::class, 'index']);
        Route::post('/', [RefaccionController::class, 'store']);
        Route::post('/masivo', [RefaccionController::class, 'storeMasivo']);
        Route::get('/{idRefaccion}', [RefaccionController::class, 'show']);
        Route::put('/{idRefaccion}', [RefaccionController::class, 'update']);
    });

    // ── Compatibilidad vehicular ────────────────────────────────────────
    Route::prefix('compatibilidad')->group(function () {
        Route::get('/catalogos-vehiculos', [CompatibilidadController::class, 'catalogosVehiculos']);
        Route::post('/reglas', [CompatibilidadController::class, 'storeRegla']);
        Route::get('/reglas/refaccion/{idRefaccion}', [CompatibilidadController::class, 'reglasDeRefaccion']);
        Route::delete('/reglas/{idRegla}', [CompatibilidadController::class, 'destroyRegla']);
        Route::post('/buscar-compatibles', [CompatibilidadController::class, 'buscarCompatibles']);
    });

    // ── Ventas (punto de venta / bitácora) ──────────────────────────────
    Route::prefix('ventas')->group(function () {
        Route::get('/', [VentaController::class, 'index']);
        Route::post('/', [VentaController::class, 'store']);
        Route::get('/corte', [VentaController::class, 'ventasCorte']);
        Route::get('/{idVenta}', [VentaController::class, 'show']);
    });

    // ── Cotizaciones ────────────────────────────────────────────────────
    Route::post('/cotizaciones', [CotizacionController::class, 'store']);

    // ── Cortes de caja ──────────────────────────────────────────────────
    Route::prefix('cortes')->group(function () {
        Route::get('/', [CorteController::class, 'index']);
        Route::post('/', [CorteController::class, 'store']);
        Route::get('/desglosado', [CorteController::class, 'desglosado']);
        Route::post('/evidencias', [CorteController::class, 'subirEvidencias']);
        Route::get('/{idCorte}', [CorteController::class, 'show']);
        Route::post('/{idCorte}/cerrar', [CorteController::class, 'cerrar']);
    });

    // ── Requisiciones ───────────────────────────────────────────────────
    Route::prefix('requisiciones')->group(function () {
        Route::get('/', [RequisicionController::class, 'index']);
        Route::get('/{idRequisicion}', [RequisicionController::class, 'show']);
        Route::put('/{idRequisicion}', [RequisicionController::class, 'update']);
        Route::get('/{idRequisicion}/por-proveedor', [RequisicionController::class, 'porProveedor']);
    });

    // ── Órdenes de compra ───────────────────────────────────────────────
    Route::prefix('ordenes-compra')->group(function () {
        Route::get('/', [OrdenCompraController::class, 'index']);
        Route::post('/', [OrdenCompraController::class, 'store']);
        Route::get('/{idOrdenCompra}', [OrdenCompraController::class, 'show']);
        Route::put('/{idOrdenCompra}/gestionar', [OrdenCompraController::class, 'gestionar']);
        Route::get('/{idOrdenCompra}/pdf', [OrdenCompraController::class, 'pdf']);
    });

    // ── Embarques ───────────────────────────────────────────────────────
    Route::prefix('embarques')->group(function () {
        Route::get('/', [EmbarqueController::class, 'index']);
        Route::post('/', [EmbarqueController::class, 'store']);
        Route::get('/refacciones-insertadas', [EmbarqueController::class, 'refaccionesInsertadas']);
        Route::get('/refaccion/{idRefaccion}', [EmbarqueController::class, 'embarquesDeRefaccion']);
        Route::get('/pre-registro/{idPreRegistro}', [EmbarqueController::class, 'embarquesDePreRegistro']);
        Route::get('/{idEmbarque}', [EmbarqueController::class, 'show']);
        Route::post('/{idEmbarque}/aprobar', [EmbarqueController::class, 'aprobar']);
        Route::post('/{idEmbarque}/rechazar', [EmbarqueController::class, 'rechazar']);
    });

    // ── Gastos ──────────────────────────────────────────────────────────
    Route::prefix('gastos')->group(function () {
        Route::get('/', [GastoController::class, 'index']);
        Route::post('/', [GastoController::class, 'store']);
        Route::post('/movil', [GastoController::class, 'storeMovil']);
        Route::get('/tipos', [GastoController::class, 'tipos']);
        Route::post('/tipos', [GastoController::class, 'storeTipo']);
        Route::get('/categorias', [GastoController::class, 'categorias']);
    });

    // ── Repartos ────────────────────────────────────────────────────────
    Route::prefix('repartos')->group(function () {
        Route::get('/', [RepartoController::class, 'index']);
        Route::post('/', [RepartoController::class, 'store']);
        Route::get('/ordenes-pendientes', [RepartoController::class, 'ordenesPendientes']);
        Route::get('/repartidores', [RepartoController::class, 'repartidores']);
        Route::post('/asignar', [RepartoController::class, 'asignar']);
        Route::get('/ordenes-asignadas/{idRepartidor}', [RepartoController::class, 'ordenesAsignadas']);
        Route::get('/orden/{idOrden}', [RepartoController::class, 'detalleOrden']);
        Route::get('/{idOrden}', [RepartoController::class, 'detalleReparto']);
    });

    // ── Dashboard ───────────────────────────────────────────────────────
    Route::prefix('dashboard')->group(function () {
        Route::get('/ventas-pagadas-por-dia', [DashboardController::class, 'ventasPagadasPorDia']);
        Route::get('/ventas-hoy', [DashboardController::class, 'ventasHoy']);
        Route::get('/total-ventas-hoy', [DashboardController::class, 'acumuladoVentasHoy']);
        Route::get('/ordenes-en-reparto-hoy', [DashboardController::class, 'ordenesEnRepartoHoy']);
        Route::get('/ordenes-compra-hoy', [DashboardController::class, 'ordenesCompraHoy']);
        Route::get('/requisiciones-aprobadas-hoy', [DashboardController::class, 'requisicionesAprobadasHoy']);
        Route::get('/top5-clientes', [DashboardController::class, 'top5Clientes']);
        Route::get('/top5-refacciones-vendidas', [DashboardController::class, 'top5RefaccionesVendidas']);
        Route::get('/ventas-metodos-hoy', [DashboardController::class, 'ventasPorMetodoPagoHoy']);
        Route::get('/top5-refaccionistas', [DashboardController::class, 'top5Refaccionistas']);
        Route::get('/refacciones-criticas', [DashboardController::class, 'refaccionesCriticas']);
        Route::get('/top-proveedores', [DashboardController::class, 'topProveedores']);
    });

    // ── Catálogos (solo lectura) ────────────────────────────────────────
    Route::get('/catalogos/{catalogo}', [CatalogoController::class, 'show']);

    // ── Versiones de la aplicación ──────────────────────────────────────
    Route::get('/versiones', [VersionController::class, 'index']);
    Route::get('/versiones/ultima', [VersionController::class, 'ultima']);

    // ── Módulos ─────────────────────────────────────────────────────────
    Route::prefix('modulos')->group(function () {
        Route::get('/', [ModuloController::class, 'index']);
        Route::get('/categorias', [ModuloController::class, 'categorias']);
    });
});
