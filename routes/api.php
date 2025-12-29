<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


Route::post('registrar-usuario', [UserController::class, 'registrarUsuario']);
Route::post('login',[userController::class, 'login']);
Route::get('perfil-de-usuario/{id_usuario}', [UserController::class, 'perfilUsuario']);
Route::get('listar-usuarios', 'App\Http\Controllers\Admin\UserController@getUsuarios');
Route::get('tipos-usuarios', 'App\Http\Controllers\Catalogos\TipoUsuarioController@getAll');


//          |---------------------      Rutas de Configuración     ---------------------|

Route::get('categorias-modulos', 'App\Http\Controllers\Catalogos\CategoriaModuloController@getCategoriasModulos');
Route::get('modulos-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\ModuloController@getModulosUsuario');
Route::put('modulos/actualizar-modulos-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\ModuloController@actualizarModulosUsuario');
Route::put('user/actualizar-modulo-web-movil/{id_usuario}', 'App\Http\Controllers\Admin\UserController@actualizarModuloWebMovil');
Route::get('modulos-disponibles', 'App\Http\Controllers\Catalogos\ModuloController@getAllModulos');
Route::put('TipoUsuario/actualizar-tipo-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\TipoUsuarioController@actualizarTipoUsuario');
Route::put('User/actualizar-estado-usuario/{id_usuario}', 'App\Http\Controllers\Admin\UserController@actualizarEstatusUsuario');


//          |---------------------      Catalogos     ---------------------|
Route::get('mostrar-marcas-refacciones', 'App\Http\Controllers\Catalogos\MarcaRefaccionController@getMarcasRefacciones');
Route::get('mostrar-categorias-refacciones', 'App\Http\Controllers\Catalogos\CategoriaRefaccionController@getCategoriasRefacciones');
Route::get('mostrar-subcategorias-refacciones', 'App\Http\Controllers\Catalogos\SubcategoriaRefaccionController@getSubcategoriasRefacciones');
Route::get('mostrar-clases-refacciones', 'App\Http\Controllers\Catalogos\ClaseRefaccionController@getClasesRefacciones');
Route::get('mostrar-unidades-medida', 'App\Http\Controllers\Catalogos\UnidadMedidaController@getUnidadesMedida');
Route::get('mostrar-posiciones-vehiculo', 'App\Http\Controllers\Catalogos\PosicionVehiculoController@getPosicionesVehiculo');
Route::get('mostrar-ubicaciones-almacen', 'App\Http\Controllers\Catalogos\UbicacionAlmacenController@getUbicacionesAlmacen');
Route::get('mostrar-porcentajes-utilidad', 'App\Http\Controllers\Catalogos\PorcentajeUtilidadController@getPorcentajesUtilidad');
Route::get('mostrar-metodos-pagos', 'App\Http\Controllers\Catalogos\MetodoPagoController@getMetodosPagos');
Route::get('mostrar-tipos-creditos', 'App\Http\Controllers\Catalogos\TipoCreditoController@getTiposCreditos');

Route::get('mostrar-clientes', 'App\Http\Controllers\ClienteController@getClientes');
Route::get('mostrar-cuentas-bancarias', 'App\Http\Controllers\Catalogos\CuentaBancariaController@getCuentasBancarias');
Route::get('tipos-empleados', 'App\Http\Controllers\Catalogos\TipoEmpleadoController@getAll');
Route::get('profesiones', 'App\Http\Controllers\Catalogos\ProfesionesController@getAll');
Route::get('grados-estudios', 'App\Http\Controllers\Catalogos\GradoEstudioController@getAll');
Route::get('sucursales', 'App\Http\Controllers\Catalogos\SucursalController@getAll');
Route::get('tipos-cliente', 'App\Http\Controllers\Catalogos\TipoClienteController@getAll');




//          |---------------------      Refacciones     ---------------------|
Route::get('mostrar-refacciones', 'App\Http\Controllers\RefaccionController@mostrarRefacciones');
Route::get('mostrar-refaccion-id/{id_refaccion}', 'App\Http\Controllers\RefaccionController@mostrarRefaccionId');
Route::put('editar-refaccion/{id_refaccion}', 'App\Http\Controllers\RefaccionController@actualizarRefaccion');
Route::post('crear-refaccion', 'App\Http\Controllers\RefaccionController@crearRefaccion');
Route::post('crear-refacciones-masivo', 'App\Http\Controllers\RefaccionController@crearRefaccionesMasivo');









//          |---------------------      Equivalencias     ---------------------|




//          |---------------------      Ventas     ---------------------|
Route::get('mostrar-ventas', 'App\Http\Controllers\VentaController@getVentas');
Route::post('crear-venta', 'App\Http\Controllers\VentaController@crearVenta');
Route::get('ventas-corte', 'App\Http\Controllers\VentaController@getVentasCorte');
Route::get('venta-detalle/{id_venta}', 'App\Http\Controllers\VentaController@getVentaById');




//          |---------------------      Requisiciones     ---------------------|
Route::get('mostrar-requisiciones', 'App\Http\Controllers\RequisicionController@mostrarRequisiciones');
Route::get('mostrar-requisicion/{id_requisicion}', 'App\Http\Controllers\RequisicionController@mostrarRequisicionByID');
Route::put('actualizar-requisicion/{id_requisicion}', 'App\Http\Controllers\RequisicionController@actualizarRequisicion');



Route::get('mostrar-requisicion-por-proveedor/{id_requisicion}', 'App\Http\Controllers\RequisicionRefaccionController@previsualizarPorProveedor');





//          |---------------------      Ordenes Compras     ---------------------|
Route::get('mostrar-ordenes-compras', 'App\Http\Controllers\OrdenCompraController@mostrarOrdenesCompras');
Route::get('mostrar-orden-compra/{id_orden_compra}', 'App\Http\Controllers\OrdenCompraController@mostrarOrdenCompra');
Route::get('descargar-orden-compra-pdf/{id_orden_compra}', 'App\Http\Controllers\OrdenCompraController@generarOrdenCompraPDF');
Route::put('gestionar-orden-compra/{id_orden_compra}', 'App\Http\Controllers\OrdenCompraController@gestionarOrdenCompra');
Route::post('crear-ordenes-compras', 'App\Http\Controllers\OrdenCompraController@generarOrdenesCompra');









//          |---------------------      Proveedores     ---------------------|
Route::get('mostrar-proveedores', 'App\Http\Controllers\ProveedorController@getProveedores');
Route::get('proveedor/listar-proveedores', 'App\Http\Controllers\ProveedorController@getAll');
Route::post('proveedor/crear-proveedor', 'App\Http\Controllers\ProveedorController@crearProveedor');
Route::put('proveedor/actualizar-proveedor/{id_proveedor}', 'App\Http\Controllers\ProveedorController@actualizarProveedor');


//          |---------------------      Rutas de clientes     ---------------------|
Route::get('cliente/ver-clientes', 'App\Http\Controllers\ClienteController@getClientes');
Route::get('cliente/listar-clientes', 'App\Http\Controllers\ClienteController@getAll');
Route::post('cliente/crear-cliente', 'App\Http\Controllers\ClienteController@crearCliente');
Route::put('cliente/actualizar-cliente/{id_cliente}', 'App\Http\Controllers\ClienteController@actualizarCliente');

//          |---------------------      Rutas de empleados     ---------------------|
Route::get('empleado/listar-empleados', 'App\Http\Controllers\EmpleadoController@listarEmpleados');
Route::get('empleado/listar-empleados-por-usuario/{id_usuario}', 'App\Http\Controllers\EmpleadoController@listarEmpleadosPorUsuario');
Route::get('empleado/listar-empleados-sin-usuario', 'App\Http\Controllers\EmpleadoController@listarEmpleadosSinUsuario');
Route::post('empleado/crear-empleado', 'App\Http\Controllers\EmpleadoController@crearEmpleado');
Route::put('empleado/actualizar-empleado/{id_empleado}', 'App\Http\Controllers\EmpleadoController@actualizarEmpleado');
Route::get('empleado/habilidades-empleados/{id_empleado}', 'App\Http\Controllers\EmpleadoController@obtenerHabilidadesEmpleado');
Route::put('empleado/actualizar-habilidades-empleado/{id_empleado}', 'App\Http\Controllers\EmpleadoController@actualizarHabilidadesEmpleado');
Route::get('empleado/empleados-gerente/{id_sucursal}', 'App\Http\Controllers\EmpleadoController@obtenerGerenteSucursal');
Route::get('empleado/empleados-sucursal/{id_sucursal}', 'App\Http\Controllers\EmpleadoController@listarEmpleadosPorSucursal');







//          |---------------------      VagXpressMovil     ---------------------|
Route::post('login-movil', 'App\Http\Controllers\Movil\UserMovilController@login');
Route::get('gastos/tipos', 'App\Http\Controllers\Movil\GastoController@getTiposGastos');
Route::post('gastos/crear-gasto-movil', 'App\Http\Controllers\Movil\GastoController@crearGastoMovil');
Route::post('crear-embarque', 'App\Http\Controllers\Movil\EmbarqueController@crearEmbarque');
Route::get('mostrar-embarques', 'App\Http\Controllers\Movil\EmbarqueController@getAllEmbarques');
Route::get('mostrar-embarque/{id_embarque}', 'App\Http\Controllers\Movil\EmbarqueController@getEmbarque');
Route::post('aprobar-embarque/{id_embarque}', 'App\Http\Controllers\Movil\EmbarqueController@aprobarEmbarque');
Route::post('rechazar-embarque/{id_embarque}', 'App\Http\Controllers\Movil\EmbarqueController@rechazarEmbarque');
Route::get('embarques-refacciones-insertadas/{id_refaccion}', 'App\Http\Controllers\Movil\EmbarqueController@embarquesRefaccionesInsertadas');
Route::get('refacciones-insertadas', 'App\Http\Controllers\Movil\EmbarqueController@getRefaccionesInsertadas');
Route::get('embarques-refacciones-insertadas-nuevas/{id_pre_registro_refaccion}', 'App\Http\Controllers\Movil\EmbarqueController@embarquesRefaccionesInsertadasNuevas');










// =======================
// CORTES
// =======================

Route::get('mostrar-cortes', 'App\Http\Controllers\CorteController@index');
Route::get('mostrar-corte-id/{id_corte}','App\Http\Controllers\CorteController@mostrarCortePorId');
Route::post('crear-corte','App\Http\Controllers\CorteController@crearCorte');
Route::post('subir-evidencias-corte','App\Http\Controllers\CorteController@subirEvidenciasCorte');
Route::post('cerrar-corte/{id_corte}','App\Http\Controllers\CorteController@cerrar');
Route::post('crear-corte-evidencia','App\Http\Controllers\CorteController@storeEvidencia');
Route::get('corte-caja-desglosado', 'App\Http\Controllers\CorteController@getCorteCajaDesglosado');













//          |---------------------      VagXpressMovil-Repartidores     ---------------------|
Route::get('ordenes-asignadas/{id_repartidor}','App\Http\Controllers\Movil\RepartidorController@getOrdenesAsignadas');
Route::get('detalle-orden/{id_orden}','App\Http\Controllers\Movil\RepartidorController@getDetalleOrden');
Route::post('crear-reparto','App\Http\Controllers\Movil\RepartidorController@crearReparto');
Route::get('ordenes-pendientes','App\Http\Controllers\Movil\AsignacionController@getAllOrdenesPendientes');
Route::get('repartidores','App\Http\Controllers\Movil\AsignacionController@getAllRepartidores');
Route::post('asignar-orden-repartidor','App\Http\Controllers\Movil\AsignacionController@asignarOrdenRepartidor');
Route::get('repartos','App\Http\Controllers\Movil\RepartidorController@getAllRepartos');
Route::get('detalle-reparto/{id_orden}','App\Http\Controllers\Movil\RepartidorController@getDetalleReparto');
