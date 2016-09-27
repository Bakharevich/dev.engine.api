<?php

namespace App\Http\Middleware;

use Closure;

class DefineSite
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
        $request->domain = $_SERVER['HTTP_HOST'];
        
        return $next($request);
    }
}
