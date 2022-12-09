<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use App\Models\Mahasiswa;
use Exception;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $token = $request->header('token') ?? $request->query('token');

    if (!$token) {
      return response()->json([
        'error' => 'Token tidak ada'
      ]);
    }
    try {
      $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
    } catch (ExpiredException $e) {
      return response()->json([
        'error' => 'Provided token is expired.'
      ], 400);
    } catch (Exception $e) {
      return response()->json([
        'error' => 'An error while decoding token.'
      ], 400);
    }

    $user = Mahasiswa::find($credentials->sub);

    $request->user = $user;
    return $next($request);
  }
}
