<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController; // Corrección en el espacio de nombres del controlador
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/usuario',[UsuarioController::class,'index']);
Route::get('/usuario/{id}',[UsuarioController::class,'show']);
Route::post('/usuario',[UsuarioController::class,'create']);
Route::put('/usuario/{id}',[UsuarioController::class,'actualizar_usuario']);
Route::put('/usuario-estado/{id}',[UsuarioController::class,'actualizar_estado_usuario']);