<?php

namespace App\Http\Middleware;

use Closure;

class adminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->privilege == 'admin')
        {
           return $next($request);
        }
        else
        {
           return redirect('error_404');
        }
        return $next($request);
    }
}
