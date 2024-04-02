<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Exception;
use Illuminate\Support\Facades\Config;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        $jwtSecret = Config::get('app.jwt_secret');

        try {
            $decoded = JWT::decode($token, $jwtSecret, array('HS256'));
            // Additional validation can be performed here if needed
            return $next($request);
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }
}
