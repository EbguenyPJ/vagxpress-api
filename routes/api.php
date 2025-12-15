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


//          |---------------------      Refacciones     ---------------------|
Route::get('mostrar-refacciones', 'App\Http\Controllers\RefaccionController@mostrarRefacciones');
Route::get('mostrar-refaccion-id/{id_refaccion}', 'App\Http\Controllers\RefaccionController@mostrarRefaccionId');
Route::put('editar-refaccion/{id_refaccion}', 'App\Http\Controllers\RefaccionController@actualizarRefaccion');
Route::post('crear-refaccion', 'App\Http\Controllers\RefaccionController@crearRefaccion');
Route::post('crear-refacciones-masivo', 'App\Http\Controllers\RefaccionController@crearRefaccionesMasivo');



//          |---------------------      Equivalencias     ---------------------|




//          |---------------------      Ventas     ---------------------|
Route::post('crear-venta', 'App\Http\Controllers\VentaController@crearVenta');


//          |---------------------      Proveedores     ---------------------|
Route::get('mostrar-proveedores', 'App\Http\Controllers\ProveedorController@getProveedores');










































//          |---------------------      VagXpressMovil     ---------------------|
Route::post('login-movil', 'App\Http\Controllers\Movil\UserMovilController@login');
Route::get('gastos/tipos', 'App\Http\Controllers\Movil\GastoController@getTiposGastos');
Route::post('gastos/crear-gasto-movil', 'App\Http\Controllers\Movil\GastoController@crearGastoMovil');
