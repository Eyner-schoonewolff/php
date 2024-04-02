<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Obtener los datos del usuario a partir de la solicitud (correo y contraseña)
            $correo = $request->input('correo');
            $contrasenia = $request->input('contrasenia');

            // Buscar al usuario en la base de datos
            $usuario = $this->obtener_usuario($correo, $contrasenia);
            // Generar el token JWT para el usuario
            $token = JWTAuth::fromUser($usuario);
            // Devolver el token en la respuesta
            return response()->json(['usuario' => $usuario,'token' => $token]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function index()
    {

        $usuario = Usuario::paginate();

        if ($usuario->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'No se encuentra informacion disponible en estos momentos'], 404);
        }

        return $usuario->items();

    }
    public function show($id)
    {

        try {

            $usuario = $this->find_user($id);
            return response()->json(['status' => 'ok', 'data' => $usuario], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 404, 'message' => $e->getMessage()], 404);
        }

    }

    public function create(Request $request)
    {

        try {
            $usuario = new Usuario();

            $usuario->nombre = $request->input("nombre");
            $usuario->correo = $request->input("correo");
            $usuario->contrasenia = $this->encriptar_clave($request->input("contrasenia"));
            $usuario->direccion = $request->input("direccion");
            $usuario->telefono = $request->input("telefono");
            $usuario->fecha_nacimiento = $request->input("fecha_nacimiento");


            $usuario->save();

            return json_encode(['mensaje' => 'se ha agregado el usuario correctamente'], 201);

        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

    }

    public function actualizar_usuario($id, Request $request)
    {
        try {

            $this->find_user($id);

            Usuario::where('id', $id)->update([
                'nombre' => $request->input('nombre'),
                'correo' => $request->input('correo'),
                'contrasenia' => $this->encriptar_clave($request->input("contrasenia")),
                'direccion' => $request->input('direccion'),
                'telefono' => $request->input('telefono'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            ]);

            return json_encode(['mensaje' => 'Se ha actualizado el usuario correctamente.', 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function actualizar_estado_usuario($id, Request $request)
    {

        try {

            $this->find_user($id);

            $estado = $request->input('estado');

            Usuario::where('id', $id)->update(
                [
                    'estado' => $request->input('estado'),
                ]
            );

            $mensaje = ($estado == 1 ? "Noticia activado correctamente." : ($estado == 0 ? "Noticia desactivado correctamente." : "Estado de la Noticia actualizado correctamente."));

            return json_encode(['mensaje' => $mensaje, 'id' => $id]);

        } catch (\Exception $e) {
            return response()->json(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode());
        }


    }

    private function encriptar_clave($contrasenia)
    {
        $encriptacion_contrasenia = hash('sha256', $contrasenia);
        ;
        return $encriptacion_contrasenia;

    }


    private function find_user($id)
    {

        $usuario = Usuario::find($id);

        if (!$usuario) {
            throw new \Exception('No se encuentra información disponible para el usuario con el ID proporcionado.', 404);
        }

        return $usuario;
    }
    private function obtener_usuario($correo, $contasenia)
    {

        $usuario = Usuario::where('correo', $correo)
        ->where('contrasenia',$this->encriptar_clave($contasenia))
        ->first();


        if (!$usuario) {
            throw new \Exception('El correo o la contrasenia son incorrectas, verificar que las credenciales sean correctas.', 404);
        }

        return $usuario;
    }



}
