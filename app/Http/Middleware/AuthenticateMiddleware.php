<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure $next
     * @param  Request $request
     * @return Response
     */
    public function handle($request, Closure $next)
    {


        if (!Auth::check()) {
            return redirect()->to('login');
        }
        return $next($request);
    }
}
