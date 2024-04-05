<?php

use App\Http\Requests\Usuario;
use App\Http\Requests\AutenticacionUsuario;
use App\Http\Requests\RequestEstado;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController; 
use App\Http\Controllers\NoticiaControlador;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('jwt.verify')->group(function () {
    // Rutas protegidas aquí

    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuario', [UsuarioController::class, 'show']);


    Route::put('/usuario', function (Usuario $request) {
        return app(UsuarioController::class)->actualizar_usuario($request);
    });

    Route::put('/usuario-estado', function (RequestEstado $request){
        return app(UsuarioController::class)->actualizar_estado_usuario($request);
    });


    Route::post('/logout', [UsuarioController::class, 'logout']);
});



Route::post('/usuario', function (Usuario $usuario) {
    return app(UsuarioController::class)->create($usuario);
});

Route::post('/auth/login', function (AutenticacionUsuario $request_auth) {
    return app(UsuarioController::class)->login($request_auth);
});


Route::get('/noticia', [NoticiaControlador::class, 'obtener_noticias']);

