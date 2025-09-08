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

Route::get('modulos-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\ModuloController@getModulosUsuario');
Route::post('modulos-usuario/{id_usuario}', 'App\Http\Controllers\Catalogos\ModuloController@updateModulosUsuario');


//          |---------------------      Refacciones     ---------------------|
Route::get('mostrar-refacciones', 'App\Http\Controllers\RefaccionController@mostrarRefacciones');
Route::get('mostrar-refaccion-id/{id_refaccion}', 'App\Http\Controllers\RefaccionController@mostrarRefaccionId');




