<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AddJsonContentTypeHeader
{

    public function handle(Request $request, Closure $next)
    {
        try {
            // Obtener el token JWT del encabezado Authorization
            $token = $request->bearerToken();

            if ($token) {
                // Autenticar el usuario utilizando el token JWT
                $request->headers->set('Authorization', 'Bearer ' . $token);
                $user = JWTAuth::parseToken()->authenticate();

                // Establecer el usuario en el objeto Request para su acceso posterior si es necesario
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });

                return $next($request);
            } else {
                return response()->json(['estado' => 'Authorization header not found'], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['estado' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['estado' => 'Token inválido'], 401);
        } catch (JWTException $e) {
            return response()->json(['estado' => 'Error al procesar el token'], 401);
        }
    }
}
