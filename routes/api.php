<?php

use Illuminate\Support\Facades\Validator;
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



Route::middleware('jwt.verify')->group(function () {
    // Rutas protegidas aquí
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuario', [UsuarioController::class, 'show']);



    Route::put('/usuario', function (Request $request) {

        $id = $request->user()->id;

        $esquema_usuario = Validator::make($request->all(), [
            'nombre' => 'required|string|min:1|max:50',
            'correo' => 'required|email|unique:usuario,correo,' . $id,
            'contrasenia' => 'required|string|min:6',
            'direccion' => 'required|string|max:50',
            'telefono' => 'required|string|max:11',
            'fecha_nacimiento' => 'required|date'
        ], [
            'correo.unique' => 'El correo que usted quiere registrar ya no se encuentra disponible.'
        ]);

        if ($esquema_usuario->fails()) {
            return response()->json(['error' => $esquema_usuario->errors()], 400);
        }

        return app(UsuarioController::class)->actualizar_usuario($request);
    });

    Route::put('/usuario-estado', [UsuarioController::class, 'actualizar_estado_usuario']);
    Route::post('/logout', [UsuarioController::class, 'logout']);
});



Route::post('/usuario', function (Request $request) {

    $validacion = Validator::make(
        $request->all(),
        [
            'nombre' => 'required|string|min:1|max:50',
            'correo' => 'required|email|unique:usuario,correo',
            'contrasenia' => 'required|string|min:6',
            'direccion' => 'required|string|max:50',
            'telefono' => 'required|string|max:11',
            'fecha_nacimiento' => 'required|date'
        ],

        [
            'correo.unique' => 'el correo que usted quiere registrar ya no se encuentra disponible.'
        ]

    );

    if ($validacion->fails()) {
        return response()->json(['error' => $validacion->errors()], 400);
    }

    return app(UsuarioController::class)->create($request);
});

Route::post('/auth/login', function (Request $request) {

    try {

        $validacion = Validator::make(
            $request->all(),
            [
                'correo' => 'required|email',
                'contrasenia' => 'required|min:6',
            ],
            [
                'correo.required' => 'El correo es obligatorio',
                'correo.email' => 'Debes proporcionar un correo (example@gmail.com)',
                'contrasenia.required' => 'la contrasenña es obligatorio',
                'contrasenia.min' => 'la contrasenña debe tener minimo 6 caracteres.',
            ]
        );

        if ($validacion->fails()) {
            return response()->json(['error' => $validacion->errors()], 400);
        }

        return app(UsuarioController::class)->login($request);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
