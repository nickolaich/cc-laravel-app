<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Token
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            // already logged in?
            return $next($request);
        }
        $token = $request->header('token') ? $request->header('token') : $request->get('token');

        if (!$token){
            return response('Unauthorized (1).', 401);
        }
        $userToken = UserToken::whereToken($token)->first();
        if (!$userToken){
            return response('Unauthorized (2).', 401);
        }
        $this->auth->setUser($userToken->user);
        return $next($request);
    }
}
