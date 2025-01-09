<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check the 'role' session value
        if (!Session::has('role') || !in_array('superadmin', Session::get('role'))) {
            return redirect('/dashboard')->with('error', 'Access Denied: Superadmin role required.');
        }

        return $next($request);
    }
}
