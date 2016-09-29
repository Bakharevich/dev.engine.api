<?php

namespace App\Http\Middleware;

use Closure;
use App\Site;

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

        // check if it's domain of company
        if (!empty($request->companyDomain)) {
            $request->domain = preg_replace("|" . $request->companyDomain . "\.|", "", $request->domain);
        }

        $site = Site::with('city')->where('domain', $request->domain)->first();

        if (!isset($site->id)) {
            throw new \Exception('Invalid site host');
        }

        $request->merge(compact('site'));
        
        return $next($request);
    }
}
