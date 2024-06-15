<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AlreadyLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (Session()->has('loginId') && (url('login') == $request->url() || url('registration') == $request->url())) {
            return back();
        }
        return $next($request);
    }
}