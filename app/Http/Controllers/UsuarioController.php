<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index(){

        $usuario = Usuario::where('estado',1)->paginate();

        if($usuario->isEmpty()){
            return response ()->json(['status'=> 404,'message'=> 'No se encuentra informacion disponible en estos momentos'],404);
        }

        return $usuario->items();

    }
    public function show($id){
        return Usuario::find($id);
    }

    public function create(Request $request){

        try
        {
            $usuario = new Usuario();

            $usuario->nombre = $request->input("nombre");
            $usuario->correo = $request->input("correo");
            $usuario->contrasenia = $this->encriptar_clave($request->input("contrasenia"));
            $usuario->direccion = $request->input("direccion");
            $usuario->telefono = $request->input("telefono");
            $usuario->fecha_nacimiento = $request->input("fecha_nacimiento");

            $usuario->save();

        return json_encode(['mensaje'=> 'se ha agregado el usuario correctamente']);
        
        }catch(\Exception $e){
            return json_encode(['error'=> $e->getMessage()]);
        }

    }

    public function actualizar_usuario($id, Request $request){
        try
            {
                Usuario::where('id',$id)->update(
                    [
                        'nombre'=> $request->input('nombre'),
                        'correo'=> $request->input('correo'),
                        'contrasenia'=> $this->encriptar_clave($request->input("contrasenia")),
                        'direccion'=> $request->input('direccion'),
                        'telefono'=> $request->input('telefono'),
                        'fecha_nacimiento'=> $request->input('fecha_nacimiento'),
                    
                    ]
                );
        
                return json_encode(['mensaje'=> 'se ha actualizado el usuario correctamente.','id'=> $id]);
            }
        catch(\Exception $e){
            return json_encode(['error'=> $e->getMessage()]);
            }

    }
    public function actualizar_estado_usuario($id, Request $request){

        try{

            $estado = $request->input('estado');

            Usuario::where('id',$id)->update(
                [
                    'estado'=> $request->input('estado'),
                ]
            );
    
            $mensaje = ($estado == 1 ? "Noticia activado correctamente." : ($estado == 0 ? "Noticia desactivado correctamente." : "Estado de la Noticia actualizado correctamente."));
            error_log(json_encode($mensaje));
            
            return json_encode(['mensaje'=> $mensaje,'id'=> $id]);

        }catch(\Exception $e){
            return json_encode(['error'=> $e->getMessage()]);
        }


    }

    private function encriptar_clave($contrasenia)
    {
        $encriptacion_contrasenia = hash('sha256', $contrasenia);;
        return $encriptacion_contrasenia;

    }



}
