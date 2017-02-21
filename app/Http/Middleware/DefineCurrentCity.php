<?php

namespace App\Http\Middleware;

use Closure;
use App\City;

class DefineCurrentCity
{
    /**
     * Set current city
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set current city
        if ($_SERVER['REQUEST_URI'] != "/") {
            $matches = explode("/", $_SERVER['REQUEST_URI']);

            $possibleCityDomain = $matches[1];

            // get city
            $city = City::where('country_id', $request->site->country_id)->where('domain', $possibleCityDomain)->first();
        }

        // if there is no such city, use default from sites
        if (!isset($city->id)) {
            $city = City::where('country_id', $request->site->country_id)->where('id', $request->site->city_id)->first();
        }

        $request->merge(compact('city'));


        return $next($request);
    }
}
