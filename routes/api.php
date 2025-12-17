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
Route::post('modulos-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\ModuloController@updateModulosUsuario');


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
Route::get('tipos-empleados', 'App\Http\Controllers\Catalogos\TipoEmpleadoController@getAll');
Route::get('profesiones', 'App\Http\Controllers\Catalogos\ProfesionesController@getAll');
Route::get('grados-estudios', 'App\Http\Controllers\Catalogos\GradoEstudioController@getAll');
Route::get('sucursales', 'App\Http\Controllers\Catalogos\SucursalController@getAll');
Route::get('tipos-cliente', 'App\Http\Controllers\Catalogos\TiposClientesController@getAll');



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


//          |---------------------      Proveedores     ---------------------|
Route::get('mostrar-proveedores', 'App\Http\Controllers\ProveedorController@getProveedores');


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

