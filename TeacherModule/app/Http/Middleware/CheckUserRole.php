<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id == $role) {
            // User has the correct role, allow them to proceed
            return $next($request);
        }

        // User doesn't have the correct role, redirect them to the login page or another appropriate page
        return redirect()->route('login')->with('error', 'Access denied.');
    }
}
