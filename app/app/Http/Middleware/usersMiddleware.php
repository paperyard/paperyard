<?php

namespace App\Http\Middleware;

use Closure;

class usersMiddleware
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
        if($request->user()->privilege == 'user')
        {
           return $next($request);
        }
        else
        {
           return redirect()->back();
        }
    }
}
