<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShareUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('user_data')) {
            $userData = $request->session()->get('user_data');
            view()->share('userData', $userData);
        }
        return $next($request);
    }
}
