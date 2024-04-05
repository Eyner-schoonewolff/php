
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticiaControlador;


Route::get('/noticias', 'App\Http\Controllers\NoticiaControlador@index');

// Route::get('/', [NoticiaControlador::class, 'obtener_noticias']);


