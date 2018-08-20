<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if($request->user()->privilege == 'admin'){
                return redirect('admin_dashboard');
            }
            if($request->user()->privilege == 'user'){
                return redirect('dashboard');
            }
        }
        return $next($request);
    }
}
