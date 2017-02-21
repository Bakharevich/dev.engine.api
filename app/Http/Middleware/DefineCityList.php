<?php

namespace App\Http\Middleware;

use Closure;
use App\City;

class DefineCityList
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
        $cities = City::where('country_id', $request->site->country_id)->where('is_active', 1)->orderBy('population', 'desc')->get();
        $request->merge(compact('cities'));

        return $next($request);
    }
}
