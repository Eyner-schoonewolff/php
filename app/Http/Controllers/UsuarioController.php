<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login($usuario)
    {
        try {

            $data_usuario = $this->obtener_usuario($usuario->input('correo'), $usuario->input('contrasenia'));

            // Generar el token JWT para el usuario
            $token = JWTAuth::fromUser($data_usuario);

            return response()->json(['usuario' => $data_usuario, 'token' => $token]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout()
    {
        try {

            Auth::guard('api')->logout();
            return response()->json(['mensaje' => 'Sesión cerrada exitosamente'], 200)->withCookie(cookie()->forget('jwt_token'));
        } catch (\Exception $e) {
            // Manejar cualquier error que pueda ocurrir al cerrar la sesión
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {

            $usuario = Usuario::where('estado', 1)->paginate();

            if ($usuario->isEmpty()) {
                return response()->json(['status' => 404, 'message' => 'No se encuentra informacion disponible en estos momentos'], 404);
            }

            return $usuario->items();
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function show()
    {
        try {

            error_log(json_encode($this->request->user()->id));
            $usuario = $this->find_user($this->request->user()->id);

            return response()->json(['status' => 'ok', 'data' => $usuario], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 404, 'message' => $e->getMessage()], 404);
        }
    }

    public function create($usuario_data)
    {

        try {

            $usuers = Usuario::create($usuario_data->all());

            return response()->json(['id_usuario' => $usuers->getId(), 'mensaje' => 'se ha agregado el usuario correctamente'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function actualizar_usuario($request)
    {
        try {

            $id = $this->request->user()->id;

            $this->find_user($id);

            Usuario::where('id', $id)->update(
                [
                    'nombre' => $request->input('nombre'),
                    'correo' => $request->input('correo'),
                    'contrasenia' => $this->encriptar_clave($request->input("contrasenia")),
                    'direccion' => $request->input('direccion'),
                    'telefono' => $request->input('telefono'),
                    'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                ]
            );

            return response()->json(['mensaje' => 'Se ha actualizado el usuario correctamente.', 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function actualizar_estado_usuario(Request $request)
    {
        try {

            $id_usuario = $this->request->user()->id;
            $estado = $request->input('estado');

            $this->find_user($id_usuario);

            Usuario::where('id', $id_usuario)->update(
                [
                    'estado' => $estado,
                ]
            );

            $mensaje = (

                $estado == 1 ? "usuario activado correctamente."
                : (
                    $estado == 0 ? "usuario desactivado correctamente." :
                    "Estado de la Noticia actualizado correctamente."
                )
            );

            return response()->json(['mensaje' => $mensaje, 'id' => $id_usuario]);
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getCode(), 'message' => $e->getMessage()], $e->getCode());
        }
    }

    private function encriptar_clave($contrasenia)
    {
        $encriptacion_contrasenia = hash('sha256', $contrasenia);;
        return $encriptacion_contrasenia;
    }


    private function find_user($id)
    {


        $usuario = Usuario::where('id', $id)
            ->where('estado', 1)
            ->first();

        if (!$usuario) {
            throw new \Exception('No se encuentra información disponible para el usuario con el ID proporcionado.', 404);
        }

        return $usuario;
    }
    private function obtener_usuario($correo, $contasenia)
    {

        $usuario = Usuario::where('correo', $correo)
            ->where('contrasenia', $this->encriptar_clave($contasenia))
            ->where('estado', 1)
            ->first();


        if (!$usuario) {
            throw new \Exception('El correo o la contrasenia son incorrectas, verificar que las credenciales sean correctas.', 404);
        }

        return $usuario;
    }
}
