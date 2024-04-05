<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaControlador extends Controller
{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function obtener_noticias()
    {
        try {

            $noticias = Noticia::all()->where('estado', 1);
            return response()->json($noticias, 200);
            

        } catch (\Exception $e) {
            return response()->json(
                [
                    'mensaje' => $e->getMessage()
                ],
                $e->getCode()
            );
        }
    }
}
