<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Traits\ResponseAPI;

class RefreshToken extends BaseMiddleware
{
    use ResponseAPI;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        try {
            if (!$this->auth->parseToken()->authenticate()) {
                return $this->error('Usuário não encontrado', 401);
            }

            return $next($request);

        } catch (TokenExpiredException $tee) {
            try {
                $newToken = Auth::guard($guard)->refresh();
                $user     = Auth::guard($guard)->setToken($newToken);

            } catch (JWTException $jwte) {
                return $this->error('Token inválido! Conecte-se para continuar.', 401);
            }

        } catch (JWTException $e) {
            return $this->error('Token inválido! Conecte-se para continuar.', 401);
        }

        $request->headers->set('Authorization', 'Bearer '. $newToken);
        return $next($request);
    }
}
