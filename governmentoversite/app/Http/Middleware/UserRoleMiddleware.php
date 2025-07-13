<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //dd([Auth::check() , Auth::user()->role]);
        if (Auth::check() && Auth::user()->role == $role)
        {
            return $next($request);
        }

        // throw access denied exception
        abort(403, 'Access denied');
    }
}
