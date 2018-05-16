<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->headers->get('token');

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }
}
