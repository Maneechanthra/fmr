<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session()->has('loginId')) {
            return redirect('login')->with('fail', 'You have to login first.');
        }
        return $next($request);
    }
}
